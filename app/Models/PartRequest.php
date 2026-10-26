<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartRequest extends Model
{
    protected $fillable = [
        'part_name',
        'motorcycle_brand',
        'motorcycle_model',
        'motorcycle_year',
        'customer_name',
        'phone',
        'email',
        'details',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'motorcycle_year' => 'integer',
        ];
    }
}
