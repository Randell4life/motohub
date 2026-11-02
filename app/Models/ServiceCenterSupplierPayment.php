<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceCenterSupplierPayment extends Model
{
    protected $fillable = [
        'service_center_id',
        'supplier_id',
        'invoice_id',
        'user_id',
        'amount',
        'method',
        'reference',
        'note',
        'paid_at',
    ];

    protected $casts = ['amount' => 'decimal:2', 'paid_at' => 'datetime'];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(ServiceCenterSupplier::class, 'supplier_id');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(ServiceCenterSupplierInvoice::class, 'invoice_id');
    }
}
