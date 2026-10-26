<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MotorcycleServiceRecord extends Model
{
    protected $fillable = [
        'motorcycle_id',
        'service_date',
        'mileage',
        'category',
        'title',
        'description',
        'cost',
        'notes',
    ];

protected function casts(): array
{
    return [
        'service_date' => 'date',
        'mileage' => 'integer',
        'cost' => 'decimal:2',
    ];
}
    public function motorcycle(): BelongsTo
    {
        return $this->belongsTo(Motorcycle::class);
    }
}