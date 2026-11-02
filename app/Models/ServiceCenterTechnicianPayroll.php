<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceCenterTechnicianPayroll extends Model
{
    protected $table = 'service_center_technician_payroll';

    protected $fillable = [
        'service_center_id', 'service_center_technician_id', 'work_order_id',
        'rate_type', 'rate_value', 'labor_base', 'amount', 'status',
        'earned_at', 'paid_at', 'paid_by',
    ];

    protected $casts = [
        'rate_value' => 'decimal:2',
        'labor_base' => 'decimal:2',
        'amount' => 'decimal:2',
        'earned_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function technician(): BelongsTo
    {
        return $this->belongsTo(ServiceCenterTechnician::class, 'service_center_technician_id');
    }

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function serviceCenter(): BelongsTo
    {
        return $this->belongsTo(ServiceCenter::class);
    }
}
