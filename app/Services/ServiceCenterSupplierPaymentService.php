<?php

namespace App\Services;

use App\Models\ServiceCenter;
use App\Models\ServiceCenterSupplierInvoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ServiceCenterSupplierPaymentService
{
    public function recordInvoicePayment(
        ServiceCenter $center,
        int $invoiceId,
        float $amount,
        string $method,
        ?int $userId,
        ?string $reference = null,
        ?string $note = null,
        mixed $paidAt = null,
    ) {
        return DB::transaction(function () use ($center, $invoiceId, $amount, $method, $userId, $reference, $note, $paidAt) {
            $invoice = ServiceCenterSupplierInvoice::query()
                ->where('service_center_id', $center->id)
                ->lockForUpdate()
                ->findOrFail($invoiceId);

            $paid = (float) $invoice->payments()->sum('amount');
            $remaining = round((float) $invoice->total_amount - $paid, 2);
            $amount = round($amount, 2);

            if ($amount <= 0 || $amount > $remaining) {
                throw ValidationException::withMessages([
                    'amount' => 'გადახდა ინვოისის დარჩენილ დავალიანებაზე მეტი ვერ იქნება. დარჩენილია: '.number_format(max(0, $remaining), 2).' ₾',
                ]);
            }

            return $invoice->payments()->create([
                'service_center_id' => $center->id,
                'supplier_id' => $invoice->supplier_id,
                'user_id' => $userId,
                'amount' => $amount,
                'method' => $method,
                'reference' => $reference,
                'note' => $note,
                'paid_at' => $paidAt ?? now(),
            ]);
        });
    }
}
