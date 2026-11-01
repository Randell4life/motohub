<?php

namespace App\Http\Controllers;

use App\Models\ServiceBooking;
use App\Models\ServiceCenter;
use App\Models\ServiceCenterService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Services\RewardsCheckout;

class ServiceBookingController extends Controller
{
    public function store(Request $request, ServiceCenter $serviceCenter): RedirectResponse
    {
        abort_unless($serviceCenter->is_active && $serviceCenter->approval_status === 'approved' && $serviceCenter->canUseFeature('bookings'), 404);

        $data = $request->validate([
            'service_id' => ['required', 'integer'],
            'scheduled_at' => ['required', 'date_format:Y-m-d\\TH:i'],
            'customer_name' => ['required', 'string', 'max:160'],
            'customer_phone' => ['required', 'string', 'max:40'],
            'motorcycle' => ['nullable', 'string', 'max:180'],
            'notes' => ['nullable', 'string', 'max:1500'],
            'points' => ['nullable', 'integer', 'min:0', 'max:1000000'],
        ]);

        $service = $serviceCenter->services()
            ->where('is_active', true)
            ->findOrFail($data['service_id']);

        $start = Carbon::createFromFormat('Y-m-d\\TH:i', $data['scheduled_at']);
        $end = $start->copy()->addMinutes((int) $service->duration_minutes);

        if ($start->lessThanOrEqualTo(now())) {
            throw ValidationException::withMessages([
                'scheduled_at' => 'აირჩიე მომავალი თარიღი და დრო.',
            ]);
        }

        $hours = $serviceCenter->hours()->where('day_of_week', $start->dayOfWeek)->first();
        if (!$hours || $hours->is_closed || !$hours->opens_at || !$hours->closes_at) {
            throw ValidationException::withMessages([
                'scheduled_at' => 'ამ დღეს სახელოსნო დაკეტილია. აირჩიე სხვა დღე.',
            ]);
        }

        $date = $start->format('Y-m-d');
        $opensAt = Carbon::parse($date . ' ' . $hours->opens_at);
        $closesAt = Carbon::parse($date . ' ' . $hours->closes_at);

        if ($start->lessThan($opensAt) || $end->greaterThan($closesAt)) {
            throw ValidationException::withMessages([
                'scheduled_at' => 'არჩეული დრო მომსახურების ხანგრძლივობისთვის სამუშაო საათებში არ ჯდება.',
            ]);
        }

        DB::transaction(function () use ($serviceCenter, $service, $data, $start, $end, $request) {
            // Lock the parent row so simultaneous requests for the same shop are checked serially.
            ServiceCenter::whereKey($serviceCenter->id)->lockForUpdate()->firstOrFail();

            $dayBookings = ServiceBooking::where('service_center_id', $serviceCenter->id)
                ->whereDate('scheduled_at', $start->toDateString())
                ->whereIn('status', ServiceBooking::ACTIVE_STATUSES)
                ->get(['scheduled_at', 'duration_minutes']);

            foreach ($dayBookings as $booking) {
                $existingStart = Carbon::parse($booking->scheduled_at);
                $existingEnd = $existingStart->copy()->addMinutes((int) $booking->duration_minutes);

                if ($start->lt($existingEnd) && $end->gt($existingStart)) {
                    throw ValidationException::withMessages([
                        'scheduled_at' => 'ეს დრო უკვე დაკავებულია. აირჩიე სხვა დრო ან თარიღი.',
                    ]);
                }
            }

            $discount = app(RewardsCheckout::class)->apply($request->user(), (float) $service->price, (int) ($data['points'] ?? 0), 'service-booking:' . (string) \Illuminate\Support\Str::uuid(), 'სერვისის ჯავშნის ფასდაკლება', ['service_center_id' => $serviceCenter->id]);
            ServiceBooking::create([
                'service_center_id' => $serviceCenter->id,
                'service_center_service_id' => $service->id,
                'user_id' => auth()->id(),
                'service_name' => $service->name,
                'price_at_booking' => $discount['total'],
                'points_used' => $discount['points'],
                'points_discount' => $discount['discount'],
                'duration_minutes' => $service->duration_minutes,
                'customer_name' => $data['customer_name'],
                'customer_phone' => $data['customer_phone'],
                'motorcycle' => $data['motorcycle'] ?? null,
                'scheduled_at' => $start,
                'notes' => $data['notes'] ?? null,
                'status' => 'pending',
            ]);
        });

        return redirect()->route('service-centers.show', $serviceCenter->slug)
            ->with('success', 'ჯავშნის მოთხოვნა გაიგზავნა. სახელოსნო დაგიკავშირდება დასადასტურებლად.');
    }
}

