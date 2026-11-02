<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceCenterSupplier extends Model
{
    protected $fillable = [
        'service_center_id', 'name', 'contact_person', 'phone', 'email',
        'tax_id', 'address', 'notes', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function serviceCenter(): BelongsTo
    {
        return $this->belongsTo(ServiceCenter::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(ServiceCenterSupplierPayment::class, 'supplier_id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(ServiceCenterSupplierInvoice::class, 'supplier_id');
    }
}
