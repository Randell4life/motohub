<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkOrderQuoteRevision extends Model
{
    protected $fillable = [
        'work_order_id',
        'revision_number',
        'type',
        'status',
        'diagnosis',
        'work_description',
        'labor_cost',
        'parts_cost',
        'total_cost',
        'labor_increment',
        'parts_increment',
        'additions',
        'sent_at',
        'responded_at',
    ];

    protected $casts = [
        'labor_cost' => 'decimal:2',
        'parts_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'labor_increment' => 'decimal:2',
        'parts_increment' => 'decimal:2',
        'additions' => 'array',
        'sent_at' => 'datetime',
        'responded_at' => 'datetime',
    ];

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }
}
