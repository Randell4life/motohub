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

    protected static function booted(): void
    {
        static::creating(function (self $order) {
            if (!$order->public_token) {
                $order->public_token = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    protected $fillable = [
        'order_number',
        'service_booking_id',
        'service_center_id',
        'assigned_technician_id',
        'service_center_technician_id',
        'service_center_bay_id',
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
        'quoted_labor_cost',
        'quoted_parts_cost',
        'quoted_total_cost',
        'customer_approval_status',
        'quote_sent_at',
        'customer_approved_at',
        'customer_declined_at',
        'completed_at',
        'handed_over_at',
    ];

    protected $casts = [
        'intake_odometer' => 'integer',
        'intake_photos' => 'array',
        'labor_cost' => 'decimal:2',
        'parts_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'quoted_labor_cost' => 'decimal:2',
        'quoted_parts_cost' => 'decimal:2',
        'quoted_total_cost' => 'decimal:2',
        'quote_sent_at' => 'datetime',
        'customer_approved_at' => 'datetime',
        'customer_declined_at' => 'datetime',
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
    public function parts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ServiceCenterWorkOrderPart::class);
    }

    public function payments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ServiceCenterPayment::class);
    }

    public function expenses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ServiceCenterExpense::class);
    }

    public function bay(): BelongsTo
    {
        return $this->belongsTo(ServiceCenterBay::class, 'service_center_bay_id');
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(ServiceCenterTechnician::class, 'service_center_technician_id');
    }

    public function technicianPayroll(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ServiceCenterTechnicianPayroll::class);
    }

    public function quoteRevisions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(WorkOrderQuoteRevision::class)->orderBy('revision_number');
    }

    public function activityLogs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ServiceCenterActivityLog::class);
    }

}