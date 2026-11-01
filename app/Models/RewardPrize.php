<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RewardPrize extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'period_start' => 'date',
            'awarded_at' => 'datetime',
            'points_awarded' => 'integer',
            'rank' => 'integer',
        ];
    }

    public function winner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'winner_user_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'awarded_by');
    }
}
