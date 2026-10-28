<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartListing extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'brand',
        'category',
        'condition',
        'part_number',
        'compatible_motorcycle',
        'description',
        'price',
        'image_path',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
