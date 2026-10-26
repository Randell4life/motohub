<?php

namespace App\Http\Controllers;

use App\Models\ServiceBooking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MyServiceBookingsController extends Controller
{
    public function index(Request $request): View
    {
        $userId = $request->user()->id;

        $statusLabels = [
            'pending' => 'მოლოდინში',
            'confirmed' => 'დადასტურებული',
            'completed' => 'შესრულებული',
            'cancelled' => 'გაუქმებული',
            'declined' => 'უარყოფილი',
        ];

        $status = $request->query('status');
        $allowedStatuses = array_keys($statusLabels);

        $baseQuery = ServiceBooking::query()->where('user_id', $userId);

        $counts = (clone $baseQuery)
            ->selectRaw('status, COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        $query = (clone $baseQuery)
            ->with('serviceCenter')
            ->orderByDesc('scheduled_at')
            ->orderByDesc('id');

        if (is_string($status) && in_array($status, $allowedStatuses, true)) {
            $query->where('status', $status);
        } else {
            $status = 'all';
        }

        $bookings = $query->paginate(8)->withQueryString();
        $totalCount = (clone $baseQuery)->count();
        $upcomingCount = (clone $baseQuery)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('scheduled_at', '>=', now())
            ->count();

        return view('account.service-bookings.index', compact(
            'bookings',
            'counts',
            'status',
            'statusLabels',
            'allowedStatuses',
            'totalCount',
            'upcomingCount'
        ));
    }

    public function cancel(Request $request, ServiceBooking $booking): RedirectResponse
    {
        $cancelled = DB::transaction(function () use ($request, $booking): bool {
            // Only the booking owner may cancel it. Lock the row to avoid status races.
            $ownedBooking = ServiceBooking::query()
                ->where('user_id', $request->user()->id)
                ->whereKey($booking->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (! in_array($ownedBooking->status, ['pending', 'confirmed'], true)) {
                return false;
            }

            $ownedBooking->update(['status' => 'cancelled']);

            return true;
        });

        if (! $cancelled) {
            return redirect()
                ->route('account.service-bookings.index')
                ->withErrors(['booking' => 'ამ ჯავშნის გაუქმება უკვე შეუძლებელია.']);
        }

        return redirect()
            ->route('account.service-bookings.index')
            ->with('success', 'ჯავშანი გაუქმდა.');
    }
}
