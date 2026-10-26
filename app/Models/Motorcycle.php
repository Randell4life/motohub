<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Motorcycle extends Model
{
    protected $fillable = [
        'user_id',
        'partner_id',
        'customer_id',
        'title',
        'slug',
        'brand',
        'model',
        'year',
        'price',
        'mileage',
        'engine_cc',
        'condition',
        'location',
        'description',
        'image_path',
        'status',
        'is_featured',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'price' => 'decimal:2',
            'mileage' => 'integer',
            'engine_cc' => 'integer',
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(MotorcycleImage::class)
            ->orderBy('sort_order');
    }
    public function serviceRecords(): HasMany
{
    return $this->hasMany(MotorcycleServiceRecord::class)
        ->orderByDesc('service_date');
}
public function customer(): BelongsTo
{
    return $this->belongsTo(Customer::class);
}

}
