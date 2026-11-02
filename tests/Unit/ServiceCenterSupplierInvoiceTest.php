<?php

namespace Tests\Unit;

use App\Models\ServiceCenterSupplierInvoice;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ServiceCenterSupplierInvoiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    #[DataProvider('dueDateCases')]
    public function test_invoice_is_overdue_only_after_the_due_calendar_day(string $today, string $dueDate, string $expected): void
    {
        Carbon::setTestNow(Carbon::parse($today));

        $invoice = new ServiceCenterSupplierInvoice([
            'total_amount' => 100,
            'due_date' => $dueDate,
            'payments_sum_amount' => 0,
        ]);

        $this->assertSame($expected, $invoice->payment_status);
    }

    public static function dueDateCases(): array
    {
        return [
            'due today' => ['2026-11-02 09:00:00', '2026-11-02', 'unpaid'],
            'due yesterday' => ['2026-11-02 00:01:00', '2026-11-01', 'overdue'],
            'due tomorrow' => ['2026-11-02 23:59:00', '2026-11-03', 'unpaid'],
        ];
    }

    public function test_invoice_with_partial_payment_keeps_partial_status_even_when_overdue(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-11-02 12:00:00'));

        $invoice = new ServiceCenterSupplierInvoice([
            'total_amount' => 100,
            'due_date' => '2026-11-01',
            'payments_sum_amount' => 25,
        ]);

        $this->assertSame('partial', $invoice->payment_status);
        $this->assertSame(75.0, $invoice->balance);
    }

    public function test_fully_paid_invoice_is_paid_regardless_of_due_date(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-11-02 12:00:00'));

        $invoice = new ServiceCenterSupplierInvoice([
            'total_amount' => 100,
            'due_date' => '2026-10-01',
            'payments_sum_amount' => 100,
        ]);

        $this->assertSame('paid', $invoice->payment_status);
        $this->assertSame(0.0, $invoice->balance);
    }
}
