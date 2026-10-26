<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RentalScooter extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'brand',
        'model',
        'year',
        'engine_cc',
        'daily_rate',
        'location',
        'description',
        'image_path',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'engine_cc' => 'integer',
            'daily_rate' => 'decimal:2',
        ];
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(RentalBooking::class);
    }
}