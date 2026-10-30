<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $fillable = [
        'title','slug','brand','category','part_type','part_number','compatible_motorcycle',
        'motorcycle_brand','motorcycle_model','motorcycle_year','engine_cc','description',
        'image_path','price','cost_price','stock','is_active',
    ];

    protected function casts(): array
    {
        return [
            'price'=>'decimal:2','cost_price'=>'decimal:2','stock'=>'integer',
            'is_active'=>'boolean','motorcycle_year'=>'integer','engine_cc'=>'integer',
        ];
    }
}
