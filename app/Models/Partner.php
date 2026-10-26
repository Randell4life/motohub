<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Partner extends Model
{
protected $fillable = [
    'user_id',
    'name',
    'slug',
    'type',
    'phone',
    'email',
    'address',
    'description',
    'is_active',
    'logo_path',
    'application_status',
];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function customers(): HasMany
{
    return $this->hasMany(Customer::class);
}
}
