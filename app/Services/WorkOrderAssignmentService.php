<?php

namespace App\Services;

use App\Models\ServiceCenter;
use App\Models\WorkOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WorkOrderAssignmentService
{
    /**
     * Assign a technician and/or bay without allowing overlapping active jobs.
     */
    public function assign(
        ServiceCenter $center,
        WorkOrder $workOrder,
        ?int $technicianId,
        ?int $bayId
    ): void {
        DB::transaction(function () use ($center, $workOrder, $technicianId, $bayId) {
            $lockedOrder = WorkOrder::query()
                ->where('service_center_id', $center->id)
                ->lockForUpdate()
                ->findOrFail($workOrder->id);

            if ($technicianId !== null) {
                $center->technicians()
                    ->whereKey($technicianId)
                    ->where('is_active', true)
                    ->lockForUpdate()
                    ->firstOrFail();

                $this->ensureResourceIsAvailable(
                    $center,
                    $lockedOrder,
                    'service_center_technician_id',
                    $technicianId,
                    'ეს ხელოსანი ამ დროისთვის უკვე სხვა აქტიურ შეკვეთაზეა დანიშნული.'
                );
            }

            if ($bayId !== null) {
                $center->bays()
                    ->whereKey($bayId)
                    ->where('is_active', true)
                    ->lockForUpdate()
                    ->firstOrFail();

                $this->ensureResourceIsAvailable(
                    $center,
                    $lockedOrder,
                    'service_center_bay_id',
                    $bayId,
                    'ეს სამუშაო ბოქსი ამ დროისთვის უკვე დაკავებულია.'
                );
            }

            $lockedOrder->update([
                'service_center_technician_id' => $technicianId,
                'service_center_bay_id' => $bayId,
            ]);
        });
    }

    private function ensureResourceIsAvailable(
        ServiceCenter $center,
        WorkOrder $workOrder,
        string $resourceColumn,
        int $resourceId,
        string $message
    ): void {
        $workOrder->loadMissing('serviceBooking');
        $booking = $workOrder->serviceBooking;

        // შეკვეთას ჯავშნის დრო თუ არ აქვს, დროითი კონფლიქტის დადგენა შეუძლებელია.
        if (!$booking || !$booking->scheduled_at) {
            return;
        }

        $requestedStart = Carbon::parse($booking->scheduled_at);
        $requestedEnd = $requestedStart->copy()->addMinutes(
            max(1, (int) ($booking->duration_minutes ?: 60))
        );

        $candidates = WorkOrder::query()
            ->where('service_center_id', $center->id)
            ->where($resourceColumn, $resourceId)
            ->where('id', '!=', $workOrder->id)
            ->whereNotIn('status', ['completed', 'handed_over', 'cancelled'])
            ->whereHas('serviceBooking', function ($query) use ($requestedEnd) {
                $query->whereNotNull('scheduled_at')
                    ->where('scheduled_at', '<', $requestedEnd);
            })
            ->with('serviceBooking:id,scheduled_at,duration_minutes')
            ->get();

        foreach ($candidates as $candidate) {
            $otherBooking = $candidate->serviceBooking;

            if (!$otherBooking || !$otherBooking->scheduled_at) {
                continue;
            }

            $otherStart = Carbon::parse($otherBooking->scheduled_at);
            $otherEnd = $otherStart->copy()->addMinutes(
                max(1, (int) ($otherBooking->duration_minutes ?: 60))
            );

            // Intervals overlap when each starts before the other one ends.
            if ($otherStart->lt($requestedEnd) && $otherEnd->gt($requestedStart)) {
                throw ValidationException::withMessages([
                    $resourceColumn => $message,
                ]);
            }
        }
    }
}
