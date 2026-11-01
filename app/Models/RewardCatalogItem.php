<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RewardCatalogItem extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'points_cost' => 'integer', 'stock' => 'integer', 'sort_order' => 'integer'];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(RewardCatalogOrder::class, 'item_id');
    }
}
