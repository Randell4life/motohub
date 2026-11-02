<?php

namespace Tests\Feature;

use App\Models\ServiceCenter;
use App\Models\ServiceCenterInventoryItem;
use App\Models\ServiceCenterSupplier;
use App\Models\ServiceCenterSupplierInvoice;
use App\Models\ServiceCenterSupplierInvoiceItem;
use App\Models\User;
use App\Services\ServiceCenterStockService;
use App\Services\ServiceCenterSupplierInvoiceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ServiceCenterInventoryReceivingTest extends TestCase
{
    use RefreshDatabase;

    private function makeCenter(): ServiceCenter
    {
        $owner = User::factory()->create();

        return ServiceCenter::create([
            'owner_id' => $owner->id,
            'name' => 'Inventory Test Center',
            'slug' => 'inventory-test-center-' . $owner->id,
            'phone' => '+995555000000',
            'city' => 'Tbilisi',
            'address' => 'Test address',
            'is_active' => true,
        ]);
    }

    private function makeItem(ServiceCenter $center, int $quantity = 0, float $cost = 0): ServiceCenterInventoryItem
    {
        return ServiceCenterInventoryItem::create([
            'service_center_id' => $center->id,
            'name' => 'Engine oil',
            'quantity' => $quantity,
            'min_quantity' => 1,
            'cost_price' => $cost,
            'sale_price' => 35,
            'is_active' => true,
        ]);
    }

    public function test_receiving_stock_updates_weighted_average_cost_and_records_incoming_movement(): void
    {
        $center = $this->makeCenter();
        $item = $this->makeItem($center, 10, 10);

        $updated = app(ServiceCenterStockService::class)->receive(
            $center, $item, 10, 20, null, 'Purchase receipt'
        );

        $this->assertSame(20, $updated->quantity);
        $this->assertSame(15.0, (float) $updated->cost_price);
        $this->assertDatabaseHas('service_center_inventory_movements', [
            'service_center_id' => $center->id,
            'inventory_item_id' => $item->id,
            'type' => 'in',
            'quantity' => 10,
            'unit_cost' => 20,
        ]);
    }

    public function test_stock_adjustment_cannot_reduce_quantity_below_zero(): void
    {
        $center = $this->makeCenter();
        $item = $this->makeItem($center, 2, 12);

        try {
            app(ServiceCenterStockService::class)->adjust(
                $center, $item, 'out', 3, null, 'Stock count correction'
            );
            $this->fail('Expected insufficient-stock validation exception.');
        } catch (ValidationException) {
            $this->assertSame(2, $item->fresh()->quantity);
            $this->assertDatabaseCount('service_center_inventory_movements', 0);
        }
    }

    public function test_supplier_invoice_receiving_supports_partial_receipts_and_rejects_over_receiving(): void
    {
        $center = $this->makeCenter();
        $owner = $center->owner;
        $supplier = ServiceCenterSupplier::create([
            'service_center_id' => $center->id,
            'name' => 'Test Supplier',
            'is_active' => true,
        ]);
        $item = $this->makeItem($center);

        $invoice = ServiceCenterSupplierInvoice::create([
            'service_center_id' => $center->id,
            'supplier_id' => $supplier->id,
            'user_id' => $owner->id,
            'invoice_number' => 'INV-' . $center->id,
            'issue_date' => now()->toDateString(),
            'due_date' => now()->addDays(14)->toDateString(),
            'total_amount' => 100,
        ]);

        $line = ServiceCenterSupplierInvoiceItem::create([
            'invoice_id' => $invoice->id,
            'inventory_item_id' => $item->id,
            'quantity' => 5,
            'received_quantity' => 0,
            'unit_cost' => 20,
        ]);

        $service = app(ServiceCenterSupplierInvoiceService::class);
        $service->receive($center, $line, 2, $owner->id);
        $this->assertSame(2, $line->fresh()->received_quantity);
        $this->assertSame(2, $item->fresh()->quantity);

        $service->receive($center, $line, 3, $owner->id);
        $this->assertSame(5, $line->fresh()->received_quantity);
        $this->assertSame(5, $item->fresh()->quantity);
        $this->assertDatabaseCount('service_center_inventory_movements', 2);

        try {
            $service->receive($center, $line, 1, $owner->id);
            $this->fail('Expected over-receiving validation exception.');
        } catch (ValidationException) {
            $this->assertSame(5, $line->fresh()->received_quantity);
            $this->assertSame(5, $item->fresh()->quantity);
            $this->assertDatabaseCount('service_center_inventory_movements', 2);
        }
    }
}
