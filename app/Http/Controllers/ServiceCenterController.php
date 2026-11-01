<?php

namespace App\Http\Controllers;

use App\Models\ServiceBooking;
use App\Models\ServiceCenter;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ServiceCenterController extends Controller
{
    public function index(Request $request)
    {
        $query = ServiceCenter::query()
            ->active()
            ->with(['services' => fn ($services) => $services->where('is_active', true)])
            ->withCount(['services' => fn ($services) => $services->where('is_active', true)])
            ->orderByDesc('is_verified')
            ->orderBy('name');

        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . trim($request->string('city')->toString()) . '%');
        }

        if ($request->filled('q')) {
            $term = trim($request->string('q')->toString());
            $query->where(function ($builder) use ($term) {
                $builder->where('name', 'like', "%{$term}%")
                    ->orWhere('city', 'like', "%{$term}%")
                    ->orWhere('address', 'like', "%{$term}%")
                    ->orWhereHas('services', fn ($services) => $services
                        ->where('is_active', true)
                        ->where('name', 'like', "%{$term}%"));
            });
        }

        $centers = $query->paginate(9)->withQueryString();

        return view('service-centers.index', compact('centers'));
    }

    public function show(ServiceCenter $serviceCenter)
    {
        abort_unless($serviceCenter->is_active && $serviceCenter->approval_status === 'approved' && $serviceCenter->canUseFeature('bookings'), 404);

        $serviceCenter->load([
            'services' => fn ($query) => $query->where('is_active', true)->orderBy('name'),
            'hours' => fn ($query) => $query->orderBy('day_of_week'),
        ]);

        return view('service-centers.show', compact('serviceCenter'));
    }

    /** Return appointment start slots for one active service and date. */
    public function availability(Request $request, ServiceCenter $serviceCenter)
    {
        abort_unless($serviceCenter->is_active && $serviceCenter->approval_status === 'approved', 404);

        $data = $request->validate([
            'service_id' => ['required', 'integer'],
            'date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
        ]);

        $service = $serviceCenter->services()
            ->where('is_active', true)
            ->findOrFail($data['service_id']);

        $date = Carbon::createFromFormat('Y-m-d', $data['date'])->startOfDay();
        $dateString = $date->toDateString();
        $hours = $serviceCenter->hours()
            ->where('day_of_week', $date->dayOfWeek)
            ->first();

        if (!$hours || $hours->is_closed || !$hours->opens_at || !$hours->closes_at) {
            return response()->json([
                'slots' => [],
                'message' => 'ამ დღეს სახელოსნო დაკეტილია. აირჩიე სხვა დღე.',
            ]);
        }

        $opensAt = Carbon::parse($dateString . ' ' . $hours->opens_at);
        $closesAt = Carbon::parse($dateString . ' ' . $hours->closes_at);

        if ($closesAt->lessThanOrEqualTo($opensAt)) {
            return response()->json([
                'slots' => [],
                'message' => 'სამუშაო საათები არასწორად არის მითითებული.',
            ]);
        }

        $bookings = ServiceBooking::query()
            ->where('service_center_id', $serviceCenter->id)
            ->whereDate('scheduled_at', $dateString)
            ->whereIn('status', ServiceBooking::ACTIVE_STATUSES)
            ->get(['scheduled_at', 'duration_minutes']);

        // Generate starts at 15-minute boundaries, while respecting the shop's opening time.
        $cursor = $opensAt->copy();
        $remainder = $cursor->minute % 15;
        if ($remainder !== 0) {
            $cursor->addMinutes(15 - $remainder)->setSecond(0);
        } else {
            $cursor->setSecond(0);
        }

        $serviceDuration = (int) $service->duration_minutes;
        $now = now();
        $slots = [];

        while ($cursor->copy()->addMinutes($serviceDuration)->lessThanOrEqualTo($closesAt)) {
            $slotStart = $cursor->copy();
            $slotEnd = $slotStart->copy()->addMinutes($serviceDuration);
            $isPast = $slotStart->lessThanOrEqualTo($now);
            $isOverlapping = false;

            foreach ($bookings as $booking) {
                $bookingStart = Carbon::parse($booking->scheduled_at);
                $bookingEnd = $bookingStart->copy()->addMinutes((int) $booking->duration_minutes);

                if ($slotStart->lessThan($bookingEnd) && $slotEnd->greaterThan($bookingStart)) {
                    $isOverlapping = true;
                    break;
                }
            }

            $available = !$isPast && !$isOverlapping;
            $slots[] = [
                'value' => $slotStart->format('H:i'),
                'label' => $slotStart->format('H:i'),
                'available' => $available,
                'reason' => $isPast ? 'გასულია' : ($isOverlapping ? 'დაკავებულია' : ''),
            ];

            $cursor->addMinutes(15);
        }

        return response()->json([
            'slots' => $slots,
            'message' => count(array_filter($slots, fn ($slot) => $slot['available'])) === 0
                ? 'ამ თარიღზე თავისუფალი საათი აღარ არის. აირჩიე სხვა დღე.'
                : '',
        ]);
    }
}
