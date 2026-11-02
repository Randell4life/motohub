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

            $allowedTransitions = [
                'received' => ['diagnostics', 'cancelled'],
                'diagnostics' => ['waiting_for_parts', 'in_repair', 'cancelled'],
                'waiting_for_parts' => ['diagnostics', 'in_repair', 'cancelled'],
                'in_repair' => ['waiting_for_parts', 'completed', 'cancelled'],
                'completed' => ['handed_over'],
                'handed_over' => [],
                'cancelled' => [],
            ];

            if ($nextStatus === 'in_repair' && $order->quote_sent_at && $order->customer_approval_status !== 'approved') {
                throw ValidationException::withMessages([
                    'status' => 'შეკეთების დაწყებამდე საჭიროა კლიენტის მიერ ხარჯთაღრიცხვის დადასტურება.',
                ]);
            }

            if (! in_array($nextStatus, $allowedTransitions[$previousStatus] ?? [], true)) {
                throw ValidationException::withMessages([
                    'status' => 'სტატუსების ეს ცვლილება დაუშვებელია. დაიცავით შეკვეთის სამუშაო ეტაპების თანმიმდევრობა.',
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
