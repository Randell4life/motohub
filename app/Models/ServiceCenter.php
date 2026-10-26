<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceCenter extends Model
{
    protected $fillable = [
        'owner_id', 'name', 'slug', 'description', 'phone', 'email',
        'city', 'address', 'is_verified', 'is_active', 'approval_status',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function services(): HasMany
    {
        return $this->hasMany(ServiceCenterService::class);
    }

    public function hours(): HasMany
    {
        return $this->hasMany(ServiceCenterHour::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(ServiceBooking::class);
    }

    public function workOrders(): HasMany
{
    return $this->hasMany(WorkOrder::class);
}

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where('approval_status', 'approved');
    }
}
