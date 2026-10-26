<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RentalBooking extends Model
{
    protected $fillable = [
        'rental_scooter_id',
        'user_id',
        'start_date',
        'end_date',
        'days',
        'daily_rate',
        'total_price',
        'customer_name',
        'phone',
        'notes',
        'status',
        'admin_note',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'days' => 'integer',
            'daily_rate' => 'decimal:2',
            'total_price' => 'decimal:2',
        ];
    }

    public function rentalScooter(): BelongsTo
    {
        return $this->belongsTo(RentalScooter::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}