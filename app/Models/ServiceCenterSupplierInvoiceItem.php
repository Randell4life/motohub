<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceCenterSupplierInvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id', 'inventory_item_id', 'quantity', 'received_quantity', 'unit_cost',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'received_quantity' => 'integer',
        'unit_cost' => 'decimal:2',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(ServiceCenterSupplierInvoice::class, 'invoice_id');
    }

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(ServiceCenterInventoryItem::class, 'inventory_item_id');
    }
}
