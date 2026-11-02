<?php

namespace App\Services;

use App\Models\ServiceBooking;
use App\Models\WorkOrder;
use Illuminate\Support\Facades\DB;

class ServiceBookingAutomation
{
    /**
     * Keep web and mobile booking status changes on the same workflow.
     */
    public function updateStatus(ServiceBooking $booking, string $status): ?WorkOrder
    {
        return DB::transaction(function () use ($booking, $status) {
            $lockedBooking = ServiceBooking::query()
                ->whereKey($booking->id)
                ->lockForUpdate()
                ->firstOrFail();

            $lockedBooking->update(['status' => $status]);

            if (in_array($status, ['declined', 'cancelled'], true)) {
                app(RewardsCheckout::class)->refundServiceBooking($lockedBooking);
            }

            if ($status !== 'confirmed') {
                return $lockedBooking->workOrder;
            }

            $center = $lockedBooking->serviceCenter;

            // START plans can confirm bookings but do not include work orders.
            if (! $center || ! $center->canUseFeature('work_orders')) {
                return null;
            }

            // service_booking_id is unique, so retries cannot create duplicates.
            $order = WorkOrder::firstOrCreate(
                ['service_booking_id' => $lockedBooking->id],
                [
                    'order_number' => 'WO-' . now()->format('Ymd') . '-' . strtoupper(bin2hex(random_bytes(4))),
                    'service_center_id' => $lockedBooking->service_center_id,
                    'status' => 'received',
                    'labor_cost' => (float) $lockedBooking->price_at_booking,
                    'parts_cost' => 0,
                    'total_cost' => (float) $lockedBooking->price_at_booking,
                ]
            );

            app(ServiceCenterCustomerNotifier::class)->send(
                $order,
                'booking_confirmed',
                'ჯავშანი დადასტურებულია',
                'თქვენი სერვისცენტრის ჯავშანი დადასტურდა. სამუშაო შეკვეთა: ' . $order->order_number
            );

            return $order;
        });
    }
}
