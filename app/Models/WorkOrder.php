<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkOrder extends Model
{
    public const STATUSES = [
        'received',
        'diagnostics',
        'waiting_for_parts',
        'in_repair',
        'completed',
        'handed_over',
        'cancelled',
    ];

    protected $fillable = [
        'order_number',
        'service_booking_id',
        'service_center_id',
        'assigned_technician_id',
        'status',
        'intake_odometer',
        'intake_condition',
        'intake_photos',
        'diagnosis',
        'work_description',
        'notes',
        'labor_cost',
        'parts_cost',
        'total_cost',
        'completed_at',
        'handed_over_at',
    ];

    protected $casts = [
        'intake_odometer' => 'integer',
        'intake_photos' => 'array',
        'labor_cost' => 'decimal:2',
        'parts_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'completed_at' => 'datetime',
        'handed_over_at' => 'datetime',
    ];

    public function serviceBooking(): BelongsTo
    {
        return $this->belongsTo(
            ServiceBooking::class,
            'service_booking_id'
        );
    }

    public function serviceCenter(): BelongsTo
    {
        return $this->belongsTo(ServiceCenter::class);
    }

    public function assignedTechnician(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'assigned_technician_id'
        );
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'received' => 'მიღებულია',
            'diagnostics' => 'დიაგნოსტიკა',
            'waiting_for_parts' => 'ნაწილებს ელოდება',
            'in_repair' => 'შეკეთების პროცესშია',
            'completed' => 'შეკეთებულია',
            'handed_over' => 'კლიენტს გადაეცა',
            'cancelled' => 'გაუქმებულია',
            default => $this->status,
        };
    }
}