<?php

namespace Tests\Feature;

use App\Models\ServiceCenter;
use App\Models\ServiceCenterInventoryItem;
use App\Models\User;
use App\Models\WorkOrder;
use App\Services\WorkOrderInventoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class WorkOrderInventoryServiceTest extends TestCase
{
    use RefreshDatabase;

    private function makeCenter(): ServiceCenter
    {
        $owner = User::factory()->create();

        return ServiceCenter::create([
            'owner_id' => $owner->id,
            'name' => 'Test Moto Service',
            'slug' => 'test-moto-service-' . $owner->id,
            'phone' => '+995555000000',
            'city' => 'Tbilisi',
            'address' => 'Test address',
            'is_active' => true,
        ]);
    }

    private function makeOrder(ServiceCenter $center, string $status = 'in_repair'): WorkOrder
    {
        return WorkOrder::create([
            'order_number' => 'WO-' . uniqid(),
            'service_center_id' => $center->id,
            'status' => $status,
            'labor_cost' => 50,
            'parts_cost' => 0,
            'total_cost' => 50,
        ]);
    }

    private function makeItem(ServiceCenter $center, int $quantity = 5): ServiceCenterInventoryItem
    {
        return ServiceCenterInventoryItem::create([
            'service_center_id' => $center->id,
            'name' => 'Oil filter',
            'quantity' => $quantity,
            'min_quantity' => 1,
            'cost_price' => 12,
            'sale_price' => 20,
            'is_active' => true,
        ]);
    }

    public function test_attaching_part_deducts_stock_records_movement_and_updates_order_total(): void
    {
        $center = $this->makeCenter();
        $order = $this->makeOrder($center);
        $item = $this->makeItem($center);

        $part = app(WorkOrderInventoryService::class)->attachPart($order, $item->id, 2, null);

        $this->assertSame(2, $part->quantity);
        $this->assertSame(3, $item->fresh()->quantity);
        $this->assertSame(40.0, (float) $order->fresh()->parts_cost);
        $this->assertSame(90.0, (float) $order->fresh()->total_cost);
        $this->assertDatabaseHas('service_center_inventory_movements', [
            'service_center_id' => $center->id,
            'inventory_item_id' => $item->id,
            'work_order_id' => $order->id,
            'type' => 'out',
            'quantity' => 2,
        ]);
    }

    public function test_insufficient_stock_does_not_change_inventory_or_create_part(): void
    {
        $center = $this->makeCenter();
        $order = $this->makeOrder($center);
        $item = $this->makeItem($center, 1);

        try {
            app(WorkOrderInventoryService::class)->attachPart($order, $item->id, 2, null);
            $this->fail('Expected insufficient-stock validation exception.');
        } catch (ValidationException) {
            $this->assertSame(1, $item->fresh()->quantity);
            $this->assertDatabaseCount('service_center_work_order_parts', 0);
            $this->assertDatabaseCount('service_center_inventory_movements', 0);
        }
    }

    public function test_terminal_work_order_cannot_consume_inventory(): void
    {
        $center = $this->makeCenter();
        $order = $this->makeOrder($center, 'completed');
        $item = $this->makeItem($center);

        try {
            app(WorkOrderInventoryService::class)->attachPart($order, $item->id, 1, null);
            $this->fail('Expected terminal-order validation exception.');
        } catch (ValidationException) {
            $this->assertSame(5, $item->fresh()->quantity);
            $this->assertDatabaseCount('service_center_work_order_parts', 0);
            $this->assertDatabaseCount('service_center_inventory_movements', 0);
        }
    }

    public function test_inventory_item_from_another_service_center_cannot_be_attached(): void
    {
        $center = $this->makeCenter();
        $otherCenter = $this->makeCenter();
        $order = $this->makeOrder($center);
        $foreignItem = $this->makeItem($otherCenter);

        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        app(WorkOrderInventoryService::class)->attachPart($order, $foreignItem->id, 1, null);
    }
}
