<?php

namespace Tests\Feature;

use App\Models\ServiceCenter;
use App\Models\ServiceCenterSupplier;
use App\Models\ServiceCenterSupplierInvoice;
use App\Models\User;
use App\Services\ServiceCenterSupplierPaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class SupplierInvoicePaymentGuardsTest extends TestCase
{
    use RefreshDatabase;

    private function makeCenter(): ServiceCenter
    {
        $owner = User::factory()->create();

        return ServiceCenter::create([
            'owner_id' => $owner->id,
            'name' => 'Payment Test Center',
            'slug' => 'payment-test-center-' . $owner->id,
            'phone' => '+995555000000',
            'city' => 'Tbilisi',
            'address' => 'Test address',
            'is_active' => true,
        ]);
    }

    private function makeInvoice(ServiceCenter $center, float $total = 100): ServiceCenterSupplierInvoice
    {
        $supplier = ServiceCenterSupplier::create([
            'service_center_id' => $center->id,
            'name' => 'Payment Test Supplier',
            'is_active' => true,
        ]);

        return ServiceCenterSupplierInvoice::create([
            'service_center_id' => $center->id,
            'supplier_id' => $supplier->id,
            'user_id' => $center->owner_id,
            'invoice_number' => 'PAY-' . $center->id . '-' . uniqid(),
            'issue_date' => now()->toDateString(),
            'due_date' => now()->addDays(10)->toDateString(),
            'total_amount' => $total,
        ]);
    }

    public function test_invoice_allows_partial_payments_up_to_the_remaining_balance(): void
    {
        $center = $this->makeCenter();
        $invoice = $this->makeInvoice($center);
        $service = app(ServiceCenterSupplierPaymentService::class);

        $service->recordInvoicePayment($center, $invoice->id, 30, 'bank_transfer', $center->owner_id);
        $service->recordInvoicePayment($center, $invoice->id, 70, 'cash', $center->owner_id);

        $this->assertSame(100.0, (float) $invoice->payments()->sum('amount'));
        $this->assertSame(2, $invoice->payments()->count());
        $this->assertSame(0.0, $invoice->fresh()->balance);
        $this->assertSame('paid', $invoice->fresh()->payment_status);
    }

    public function test_invoice_rejects_payment_greater_than_remaining_balance_without_recording_it(): void
    {
        $center = $this->makeCenter();
        $invoice = $this->makeInvoice($center);
        $service = app(ServiceCenterSupplierPaymentService::class);

        $service->recordInvoicePayment($center, $invoice->id, 80, 'cash', $center->owner_id);

        try {
            $service->recordInvoicePayment($center, $invoice->id, 20.01, 'cash', $center->owner_id);
            $this->fail('Expected overpayment validation exception.');
        } catch (ValidationException) {
            $this->assertSame(80.0, (float) $invoice->payments()->sum('amount'));
            $this->assertSame(1, $invoice->payments()->count());
            $this->assertSame(20.0, $invoice->fresh()->balance);
        }
    }

    public function test_invoice_from_another_service_center_cannot_be_paid(): void
    {
        $center = $this->makeCenter();
        $otherCenter = $this->makeCenter();
        $foreignInvoice = $this->makeInvoice($otherCenter);

        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        app(ServiceCenterSupplierPaymentService::class)->recordInvoicePayment(
            $center, $foreignInvoice->id, 10, 'cash', $center->owner_id
        );
    }
}
