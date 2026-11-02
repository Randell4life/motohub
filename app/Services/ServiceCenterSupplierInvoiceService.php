<?php

namespace App\Services;

use App\Models\ServiceCenter;
use App\Models\ServiceCenterInventoryItem;
use App\Models\ServiceCenterSupplierInvoice;
use App\Models\ServiceCenterSupplierInvoiceItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ServiceCenterSupplierInvoiceService
{
    public function receive(ServiceCenter $center, ServiceCenterSupplierInvoiceItem $line, int $quantity, ?int $userId): ServiceCenterSupplierInvoiceItem
    {
        return DB::transaction(function () use ($center, $line, $quantity, $userId) {
            $locked = ServiceCenterSupplierInvoiceItem::query()
                ->whereHas('invoice', fn ($query) => $query->where('service_center_id', $center->id))
                ->with('invoice')
                ->lockForUpdate()
                ->findOrFail($line->id);

            $remaining = (int) $locked->quantity - (int) $locked->received_quantity;
            if ($quantity < 1 || $quantity > $remaining) {
                throw ValidationException::withMessages([
                    'quantity' => 'მიღებული რაოდენობა დარჩენილ რაოდენობაზე მეტი ვერ იქნება. დარჩენილია: '.$remaining,
                ]);
            }

            $invoice = $locked->invoice;
            $item = ServiceCenterInventoryItem::where('service_center_id', $center->id)
                ->whereKey($locked->inventory_item_id)->firstOrFail();

            app(ServiceCenterStockService::class)->receive(
                $center,
                $item,
                $quantity,
                (float) $locked->unit_cost,
                $userId,
                'ინვოისი '.$invoice->invoice_number,
                (int) $invoice->supplier_id,
                $invoice->invoice_number,
                (int) $locked->id,
            );

            $locked->increment('received_quantity', $quantity);

            return $locked->fresh(['invoice.supplier', 'inventoryItem']);
        });
    }
}
