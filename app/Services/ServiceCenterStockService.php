<?php

namespace App\Services;

use App\Models\ServiceCenter;
use App\Models\ServiceCenterInventoryItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ServiceCenterStockService
{
    public function receive(ServiceCenter $center, ServiceCenterInventoryItem $item, int $quantity, float $unitCost, ?int $userId, ?string $note = null): ServiceCenterInventoryItem
    {
        return DB::transaction(function () use ($center, $item, $quantity, $unitCost, $userId, $note) {
            $locked = ServiceCenterInventoryItem::where('service_center_id', $center->id)->whereKey($item->id)->lockForUpdate()->firstOrFail();
            if ($quantity < 1 || $unitCost < 0) {
                throw ValidationException::withMessages(['quantity' => 'მიუთითე დადებითი რაოდენობა და სწორი თვითღირებულება.']);
            }

            $oldQuantity = (int) $locked->quantity;
            $newQuantity = $oldQuantity + $quantity;
            $weightedCost = $newQuantity > 0
                ? (((float) $locked->cost_price * $oldQuantity) + ($unitCost * $quantity)) / $newQuantity
                : $unitCost;

            $locked->update(['quantity' => $newQuantity, 'cost_price' => round($weightedCost, 2)]);
            DB::table('service_center_inventory_movements')->insert([
                'service_center_id' => $center->id,
                'inventory_item_id' => $locked->id,
                'user_id' => $userId,
                'type' => 'in',
                'quantity' => $quantity,
                'unit_cost' => $unitCost,
                'note' => $note ?: 'მარაგის მიღება',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return $locked->fresh();
        });
    }

    public function adjust(ServiceCenter $center, ServiceCenterInventoryItem $item, string $direction, int $quantity, ?int $userId, string $note): ServiceCenterInventoryItem
    {
        return DB::transaction(function () use ($center, $item, $direction, $quantity, $userId, $note) {
            $locked = ServiceCenterInventoryItem::where('service_center_id', $center->id)->whereKey($item->id)->lockForUpdate()->firstOrFail();
            if (! in_array($direction, ['in', 'out'], true) || $quantity < 1) {
                throw ValidationException::withMessages(['quantity' => 'მიუთითე კორექტირების სწორი მიმართულება და დადებითი რაოდენობა.']);
            }
            if ($direction === 'out' && $locked->quantity < $quantity) {
                throw ValidationException::withMessages(['quantity' => 'კორექტირებისთვის საწყობში საკმარისი რაოდენობა არ არის.']);
            }

            $locked->update(['quantity' => $direction === 'in'
                ? (int) $locked->quantity + $quantity
                : (int) $locked->quantity - $quantity]);

            DB::table('service_center_inventory_movements')->insert([
                'service_center_id' => $center->id,
                'inventory_item_id' => $locked->id,
                'user_id' => $userId,
                'type' => $direction === 'in' ? 'adjustment_in' : 'adjustment_out',
                'quantity' => $quantity,
                'unit_cost' => $locked->cost_price,
                'note' => $note,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return $locked->fresh();
        });
    }
}
