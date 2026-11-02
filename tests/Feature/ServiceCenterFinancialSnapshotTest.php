<?php

namespace Tests\Feature;

use App\Models\ServiceCenter;
use App\Models\ServiceCenterInventoryItem;
use App\Models\ServiceCenterSupplier;
use App\Models\ServiceCenterSupplierInvoice;
use App\Models\User;
use App\Services\ServiceCenterFinancialSnapshotService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ServiceCenterFinancialSnapshotTest extends TestCase
{
    use RefreshDatabase;

    public function test_snapshot_calculates_inventory_value_and_supplier_debt_without_double_counting_invoice_receipts(): void
    {
        $owner = User::factory()->create();
        $center = ServiceCenter::create([
            'owner_id' => $owner->id,
            'name' => 'Financial Snapshot Center',
            'slug' => 'financial-snapshot-' . $owner->id,
            'phone' => '+995555000000',
            'city' => 'Tbilisi',
            'address' => 'Test address',
            'is_active' => true,
        ]);

        $supplier = ServiceCenterSupplier::create([
            'service_center_id' => $center->id,
            'name' => 'Snapshot Supplier',
            'is_active' => true,
        ]);

        ServiceCenterInventoryItem::create([
            'service_center_id' => $center->id,
            'name' => 'Brake pads',
            'quantity' => 4,
            'min_quantity' => 1,
            'cost_price' => 25,
            'sale_price' => 40,
            'is_active' => true,
        ]);

        $invoice = ServiceCenterSupplierInvoice::create([
            'service_center_id' => $center->id,
            'supplier_id' => $supplier->id,
            'user_id' => $owner->id,
            'invoice_number' => 'SNAP-' . $center->id,
            'issue_date' => now()->toDateString(),
            'due_date' => now()->addDays(10)->toDateString(),
            'total_amount' => 150,
        ]);

        $invoice->payments()->create([
            'service_center_id' => $center->id,
            'supplier_id' => $supplier->id,
            'user_id' => $owner->id,
            'amount' => 50,
            'method' => 'bank_transfer',
            'paid_at' => now(),
        ]);

        DB::table('service_center_inventory_movements')->insert([
            'service_center_id' => $center->id,
            'inventory_item_id' => DB::table('service_center_inventory')->where('service_center_id', $center->id)->value('id'),
            'supplier_id' => $supplier->id,
            'supplier_invoice_item_id' => null,
            'user_id' => $owner->id,
            'type' => 'in',
            'quantity' => 3,
            'unit_cost' => 20,
            'note' => 'Direct supplier purchase',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $center->supplierPayments()->create([
            'supplier_id' => $supplier->id,
            'user_id' => $owner->id,
            'amount' => 10,
            'method' => 'cash',
            'paid_at' => now(),
        ]);

        $snapshot = app(ServiceCenterFinancialSnapshotService::class)->forCenter($center);

        $this->assertSame(100.0, $snapshot['inventory_value']);
        $this->assertSame(100.0, $snapshot['supplier_invoice_debt']);
        $this->assertSame(50.0, $snapshot['supplier_direct_debt']);
        $this->assertSame(150.0, $snapshot['supplier_debt']);
    }

    public function test_snapshot_is_scoped_to_the_requested_service_center(): void
    {
        $firstOwner = User::factory()->create();
        $secondOwner = User::factory()->create();

        $first = ServiceCenter::create([
            'owner_id' => $firstOwner->id, 'name' => 'First Center',
            'slug' => 'first-center-' . $firstOwner->id, 'phone' => '111',
            'city' => 'Tbilisi', 'address' => 'First', 'is_active' => true,
        ]);
        $second = ServiceCenter::create([
            'owner_id' => $secondOwner->id, 'name' => 'Second Center',
            'slug' => 'second-center-' . $secondOwner->id, 'phone' => '222',
            'city' => 'Tbilisi', 'address' => 'Second', 'is_active' => true,
        ]);

        ServiceCenterInventoryItem::create([
            'service_center_id' => $second->id, 'name' => 'Foreign stock',
            'quantity' => 9, 'min_quantity' => 0, 'cost_price' => 100,
            'sale_price' => 120, 'is_active' => true,
        ]);

        $snapshot = app(ServiceCenterFinancialSnapshotService::class)->forCenter($first);

        $this->assertSame(0.0, $snapshot['inventory_value']);
        $this->assertSame(0.0, $snapshot['supplier_debt']);
    }
}
