<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceCenterService extends Model
{
    protected $table = 'service_center_services';

    protected $fillable = [
        'service_center_id', 'name', 'description', 'price', 'price_type',
        'duration_minutes', 'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'duration_minutes' => 'integer',
        'is_active' => 'boolean',
    ];

    public function serviceCenter(): BelongsTo
    {
        return $this->belongsTo(ServiceCenter::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(ServiceBooking::class, 'service_center_service_id');
    }

    public function getPriceLabelAttribute(): string
    {
        if ($this->price_type === 'quote' || $this->price === null) {
            return 'ფასი შეთანხმებით';
        }

        $amount = number_format((float) $this->price, 0, '.', ' ');
        return ($this->price_type === 'from' ? '₾' . $amount . '-დან' : '₾' . $amount);
    }
}
