<?php

namespace App\Services;

use App\Models\ServiceCenterInventoryItem;
use App\Models\ServiceCenterWorkOrderPart;
use App\Models\WorkOrder;
use App\Models\WorkOrderQuoteRevision;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WorkOrderQuoteWorkflow
{
    public function sendInitial(WorkOrder $workOrder, array $data): WorkOrderQuoteRevision
    {
        return DB::transaction(function () use ($workOrder, $data) {
            $order = WorkOrder::query()->whereKey($workOrder->id)->lockForUpdate()->firstOrFail();

            if ($order->quoteRevisions()->where('status', 'pending')->exists()) {
                throw ValidationException::withMessages(['quote' => 'კლიენტის პასუხს უკვე გაგზავნილ ხარჯთაღრიცხვაზე უნდა დაელოდოთ.']);
            }

            if (in_array($order->status, ['in_repair', 'completed', 'handed_over', 'cancelled'], true)) {
                throw ValidationException::withMessages(['quote' => 'ამ ეტაპზე საწყისი ხარჯთაღრიცხვის გაგზავნა შეუძლებელია.']);
            }

            $labor = round((float) $data['labor_cost'], 2);
            $parts = round((float) $data['parts_cost'], 2);
            $revision = $this->nextRevision($order, [
                'type' => 'initial',
                'diagnosis' => $data['diagnosis'],
                'work_description' => $data['work_description'],
                'labor_cost' => $labor,
                'parts_cost' => $parts,
                'total_cost' => round($labor + $parts, 2),
                'labor_increment' => $labor,
                'parts_increment' => $parts,
                'additions' => [],
            ]);

            $order->update([
                'diagnosis' => $data['diagnosis'],
                'work_description' => $data['work_description'],
                'quoted_labor_cost' => $labor,
                'quoted_parts_cost' => $parts,
                'quoted_total_cost' => round($labor + $parts, 2),
                'customer_approval_status' => 'pending',
                'quote_sent_at' => now(),
                'customer_approved_at' => null,
                'customer_declined_at' => null,
            ]);

            return $revision;
        });
    }

    public function sendSupplemental(
        WorkOrder $workOrder,
        array $data,
        ?array $inventoryPart = null
    ): WorkOrderQuoteRevision {
        return DB::transaction(function () use ($workOrder, $data, $inventoryPart) {
            $order = WorkOrder::query()->whereKey($workOrder->id)->lockForUpdate()->firstOrFail();

            if (in_array($order->status, ['completed', 'handed_over', 'cancelled'], true)) {
                throw ValidationException::withMessages(['quote' => 'დამატებითი ხარჯის შეთანხმება შესაძლებელია მხოლოდ მიმდინარე შეკეთებისას.']);
            }

            if ($order->customer_approval_status === 'pending') {
                throw ValidationException::withMessages(['quote' => 'ჯერ დაელოდეთ კლიენტის მიმდინარე შეთავაზებაზე პასუხს.']);
            }

            $hasApprovedInitial = $order->quoteRevisions()->where('type', 'initial')->where('status', 'approved')->exists();
            if (! $hasApprovedInitial || ! in_array($order->customer_approval_status, ['approved', 'declined'], true)) {
                throw ValidationException::withMessages(['quote' => 'დამატებით ხარჯამდე საჭიროა საწყისი ხარჯთაღრიცხვის დადასტურება და წინა შეთავაზებაზე პასუხი.']);
            }

            $laborIncrement = round((float) ($data['labor_cost'] ?? 0), 2);
            $partsIncrement = round((float) ($data['parts_cost'] ?? 0), 2);
            $additions = [];

            if ($inventoryPart) {
                $item = ServiceCenterInventoryItem::query()
                    ->where('service_center_id', $order->service_center_id)
                    ->whereKey($inventoryPart['inventory_item_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                $quantity = (int) $inventoryPart['quantity'];
                if ($quantity < 1 || $item->quantity < $quantity) {
                    throw ValidationException::withMessages(['quantity' => 'საწყობში ამჟამად საკმარისი რაოდენობა არ არის.']);
                }

                $lineTotal = round($quantity * (float) $item->sale_price, 2);
                $partsIncrement += $lineTotal;
                $additions[] = [
                    'type' => 'inventory_part',
                    'inventory_item_id' => $item->id,
                    'name' => $item->name,
                    'quantity' => $quantity,
                    'unit_price' => (float) $item->sale_price,
                    'total' => $lineTotal,
                ];
            }

            if ($laborIncrement <= 0 && $partsIncrement <= 0) {
                throw ValidationException::withMessages(['quote' => 'დამატებითი ხარჯი ნულზე მეტი უნდა იყოს.']);
            }

            $newLabor = round((float) $order->labor_cost + $laborIncrement, 2);
            $newParts = round((float) $order->parts_cost + $partsIncrement, 2);
            $newTotal = round($newLabor + $newParts, 2);

            $revision = $this->nextRevision($order, [
                'type' => 'supplemental',
                'diagnosis' => $data['diagnosis'] ?? $order->diagnosis,
                'work_description' => trim(($order->work_description ?? '') . "\n" . ($data['description'] ?? 'დამატებითი სამუშაო')),
                'labor_cost' => $newLabor,
                'parts_cost' => $newParts,
                'total_cost' => $newTotal,
                'labor_increment' => $laborIncrement,
                'parts_increment' => $partsIncrement,
                'additions' => $additions,
            ]);

            $order->update([
                'quoted_labor_cost' => $newLabor,
                'quoted_parts_cost' => $newParts,
                'quoted_total_cost' => $newTotal,
                'customer_approval_status' => 'pending',
                'quote_sent_at' => now(),
                'customer_approved_at' => null,
                'customer_declined_at' => null,
                'status' => 'waiting_for_parts',
            ]);

            return $revision;
        });
    }

    public function decide(string $token, string $decision): WorkOrder
    {
        return DB::transaction(function () use ($token, $decision) {
            $order = WorkOrder::query()
                ->where('public_token', $token)
                ->lockForUpdate()
                ->firstOrFail();

            $revision = $order->quoteRevisions()
                ->where('status', 'pending')
                ->latest('revision_number')
                ->lockForUpdate()
                ->first();

            if (! $revision) {
                throw ValidationException::withMessages(['decision' => 'ხარჯთაღრიცხვა აღარ ელოდება პასუხს.']);
            }

            if ($decision === 'approved') {
                foreach ($revision->additions ?? [] as $addition) {
                    if (($addition['type'] ?? null) !== 'inventory_part') {
                        continue;
                    }

                    $item = ServiceCenterInventoryItem::query()
                        ->where('service_center_id', $order->service_center_id)
                        ->whereKey($addition['inventory_item_id'])
                        ->lockForUpdate()
                        ->firstOrFail();

                    $quantity = (int) $addition['quantity'];
                    if ($item->quantity < $quantity) {
                        throw ValidationException::withMessages([
                            'decision' => 'საწყობში საჭირო ნაწილის მარაგი აღარ არის. სერვისცენტრმა უნდა გამოგზავნოს განახლებული შეთავაზება.',
                        ]);
                    }

                    ServiceCenterWorkOrderPart::create([
                        'work_order_id' => $order->id,
                        'inventory_item_id' => $item->id,
                        'quantity' => $quantity,
                        'unit_price' => $addition['unit_price'],
                        'total' => $addition['total'],
                    ]);

                    $item->decrement('quantity', $quantity);

                    DB::table('service_center_inventory_movements')->insert([
                        'service_center_id' => $order->service_center_id,
                        'inventory_item_id' => $item->id,
                        'work_order_id' => $order->id,
                        'user_id' => $order->serviceCenter?->owner_id,
                        'type' => 'out',
                        'quantity' => $quantity,
                        'unit_cost' => $item->cost_price,
                        'note' => 'კლიენტის მიერ დადასტურებული დამატებითი ნაწილი · ' . $order->order_number,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                $order->update([
                    'labor_cost' => $revision->labor_cost,
                    'parts_cost' => $revision->parts_cost,
                    'total_cost' => $revision->total_cost,
                    'quoted_labor_cost' => $revision->labor_cost,
                    'quoted_parts_cost' => $revision->parts_cost,
                    'quoted_total_cost' => $revision->total_cost,
                    'customer_approval_status' => 'approved',
                    'customer_approved_at' => now(),
                ]);
                $revision->update(['status' => 'approved', 'responded_at' => now()]);
            } else {
                $order->update([
                    'customer_approval_status' => 'declined',
                    'customer_declined_at' => now(),
                ]);
                $revision->update(['status' => 'declined', 'responded_at' => now()]);
            }

            return $order->fresh();
        });
    }

    private function nextRevision(WorkOrder $order, array $attributes): WorkOrderQuoteRevision
    {
        $number = ((int) $order->quoteRevisions()->max('revision_number')) + 1;

        return $order->quoteRevisions()->create($attributes + [
            'revision_number' => $number,
            'status' => 'pending',
            'sent_at' => now(),
        ]);
    }
}
