<?php

namespace App\Services;

use App\Models\ServiceCenterInventoryItem;
use App\Models\ServiceCenterWorkOrderPart;
use App\Models\WorkOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WorkOrderInventoryService
{
    public function attachPart(
        WorkOrder $workOrder,
        int $inventoryItemId,
        int $quantity,
        ?int $userId,
    ): ServiceCenterWorkOrderPart {
        return DB::transaction(function () use ($workOrder, $inventoryItemId, $quantity, $userId) {
            $order = WorkOrder::query()
                ->whereKey($workOrder->id)
                ->where('service_center_id', $workOrder->service_center_id)
                ->lockForUpdate()
                ->firstOrFail();

            if (in_array($order->status, ['completed', 'handed_over', 'cancelled'], true)) {
                throw ValidationException::withMessages([
                    'work_order' => 'დასრულებულ, ჩაბარებულ ან გაუქმებულ შეკვეთას ნაწილის დამატება აღარ შეუძლია.',
                ]);
            }

            if ($quantity < 1) {
                throw ValidationException::withMessages([
                    'quantity' => 'რაოდენობა მინიმუმ 1 უნდა იყოს.',
                ]);
            }

            $item = ServiceCenterInventoryItem::query()
                ->where('service_center_id', $order->service_center_id)
                ->whereKey($inventoryItemId)
                ->lockForUpdate()
                ->firstOrFail();

            if (! $item->is_active) {
                throw ValidationException::withMessages([
                    'inventory_item_id' => 'ეს ნაწილი საწყობში არააქტიურია და შეკვეთაზე ვერ დაემატება.',
                ]);
            }

            if ((int) $item->quantity < $quantity) {
                throw ValidationException::withMessages([
                    'quantity' => 'საწყობში საკმარისი რაოდენობა არ არის.',
                ]);
            }

            $line = ServiceCenterWorkOrderPart::firstOrNew([
                'work_order_id' => $order->id,
                'inventory_item_id' => $item->id,
            ]);

            $line->quantity = ($line->exists ? (int) $line->quantity : 0) + $quantity;
            $line->unit_price = $item->sale_price;
            $line->total = round($line->quantity * (float) $item->sale_price, 2);
            $line->save();

            $item->decrement('quantity', $quantity);

            DB::table('service_center_inventory_movements')->insert([
                'service_center_id' => $order->service_center_id,
                'inventory_item_id' => $item->id,
                'work_order_id' => $order->id,
                'user_id' => $userId,
                'type' => 'out',
                'quantity' => $quantity,
                'unit_cost' => $item->cost_price,
                'note' => 'სამუშაო შეკვეთა ' . $order->order_number,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $partsTotal = (float) $order->parts()->sum('total');
            $order->update([
                'parts_cost' => $partsTotal,
                'total_cost' => round((float) $order->labor_cost + $partsTotal, 2),
            ]);

            return $line->fresh('inventoryItem');
        });
    }
}
