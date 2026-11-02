<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartListing extends Model
{
    protected $fillable = [
        'user_id','title','slug','brand','category','condition','part_type','part_number',
        'compatible_motorcycle','motorcycle_brand','motorcycle_model','motorcycle_year',
        'engine_cc','description','price','image_path','status','is_demo',
    ];

    protected function casts(): array
    {
        return ['price'=>'decimal:2','motorcycle_year'=>'integer','engine_cc'=>'integer','is_demo'=>'boolean'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
