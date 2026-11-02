<?php

namespace App\Services;

use App\Models\WorkOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WorkOrderLifecycle
{
    /**
     * Apply a safe, shared status transition for web and mobile clients.
     *
     * @return array{0: WorkOrder, 1: string}
     */
    public function transition(WorkOrder $workOrder, string $nextStatus): array
    {
        if (! in_array($nextStatus, WorkOrder::STATUSES, true)) {
            throw ValidationException::withMessages([
                'status' => 'შეკვეთის მითითებული სტატუსი არასწორია.',
            ]);
        }

        return DB::transaction(function () use ($workOrder, $nextStatus) {
            $order = WorkOrder::query()
                ->whereKey($workOrder->id)
                ->lockForUpdate()
                ->firstOrFail();

            $previousStatus = $order->status;

            if ($previousStatus === $nextStatus) {
                return [$order, $previousStatus];
            }

            if (in_array($previousStatus, ['handed_over', 'cancelled'], true)) {
                throw ValidationException::withMessages([
                    'status' => 'დასრულებული ან გაუქმებული შეკვეთის სტატუსის შეცვლა შეუძლებელია.',
                ]);
            }

            if ($nextStatus === 'handed_over' && $previousStatus !== 'completed') {
                throw ValidationException::withMessages([
                    'status' => 'მოტოციკლის კლიენტისთვის გადაცემა შესაძლებელია მხოლოდ შეკეთების დასრულების შემდეგ.',
                ]);
            }

            if ($nextStatus === 'cancelled' && $previousStatus === 'completed') {
                throw ValidationException::withMessages([
                    'status' => 'შეკეთებული შეკვეთა პირდაპირ ვერ გაუქმდება. გამოიყენეთ გადაცემის პროცესი.',
                ]);
            }

            $changes = ['status' => $nextStatus];

            if ($nextStatus === 'completed' && ! $order->completed_at) {
                $changes['completed_at'] = now();
            }

            if ($nextStatus === 'handed_over') {
                $changes['handed_over_at'] = now();
            }

            $order->update($changes);

            if ($nextStatus === 'handed_over' && $order->serviceBooking) {
                $order->serviceBooking()->update(['status' => 'completed']);
            }

            return [$order->fresh(), $previousStatus];
        });
    }
}
