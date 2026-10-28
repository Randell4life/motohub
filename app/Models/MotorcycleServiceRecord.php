<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MotorcycleServiceRecord extends Model
{
    protected $fillable = [
        'motorcycle_id',
        'service_center_id',
        'service_date',
        'mileage',
        'category',
        'title',
        'description',
        'cost',
        'notes',
        'verification_status',
        'verified_at',
        'verified_by_user_id',
        'verification_note',
    ];

protected function casts(): array
{
    return [
        'service_date' => 'date',
        'mileage' => 'integer',
        'cost' => 'decimal:2',
        'verified_at' => 'datetime',
    ];
}
    public function serviceCenter(): BelongsTo
    {
        return $this->belongsTo(ServiceCenter::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by_user_id');
    }

    public function motorcycle(): BelongsTo
    {
        return $this->belongsTo(Motorcycle::class);
    }
}