<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RewardCatalogOrder extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return ['points_spent' => 'integer', 'fulfilled_at' => 'datetime'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(RewardCatalogItem::class, 'item_id');
    }
}
