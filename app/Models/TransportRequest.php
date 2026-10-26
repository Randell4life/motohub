<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransportRequest extends Model
{
    protected $fillable = [
        'request_number',
        'user_id',
        'partner_id',
        'motorcycle_brand',
        'motorcycle_model',
        'motorcycle_year',
        'pickup_city',
        'pickup_address',
        'destination_city',
        'destination_address',
        'preferred_date',
        'motorcycle_details',
        'customer_name',
        'customer_phone',
        'customer_email',
        'status',
        'customer_notes',
    ];

    protected function casts(): array
    {
        return [
            'preferred_date' => 'date',
            'motorcycle_year' => 'integer',
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

    public function offers(): HasMany
    {
        return $this->hasMany(TransportOffer::class);
    }
}
