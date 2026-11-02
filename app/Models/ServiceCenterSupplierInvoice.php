<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceCenterSupplierInvoice extends Model
{
    protected $fillable = [
        'service_center_id', 'supplier_id', 'user_id', 'invoice_number',
        'issue_date', 'due_date', 'total_amount', 'file_path', 'notes',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(ServiceCenterSupplier::class, 'supplier_id');
    }

    public function serviceCenter(): BelongsTo
    {
        return $this->belongsTo(ServiceCenter::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(ServiceCenterSupplierInvoiceItem::class, 'invoice_id')->with('inventoryItem');
    }
}
