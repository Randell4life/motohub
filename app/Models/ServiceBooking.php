<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceBooking extends Model
{
    protected $table = 'service_center_bookings';

    public const ACTIVE_STATUSES = ['pending', 'confirmed'];

    protected $fillable = [
        'service_center_id', 'service_center_service_id', 'user_id', 'service_name',
        'price_at_booking', 'duration_minutes', 'customer_name', 'customer_phone',
        'motorcycle', 'scheduled_at', 'notes', 'status',
    ];

    protected $casts = [
        'price_at_booking' => 'decimal:2',
        'duration_minutes' => 'integer',
        'scheduled_at' => 'datetime',
    ];

    public function serviceCenter(): BelongsTo
    {
        return $this->belongsTo(ServiceCenter::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(ServiceCenterService::class, 'service_center_service_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'მოლოდინში',
            'confirmed' => 'დადასტურებული',
            'declined' => 'უარყოფილი',
            'cancelled' => 'გაუქმებული',
            'completed' => 'დასრულებული',
            default => $this->status,
        };
    }

    public function workOrder(): \Illuminate\Database\Eloquent\Relations\HasOne
{
    return $this->hasOne(WorkOrder::class, 'service_booking_id');
}
}
