<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommunityPost extends Model
{
    protected $fillable = ['user_id', 'motorcycle_id', 'body', 'image_path'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function motorcycle(): BelongsTo { return $this->belongsTo(Motorcycle::class); }
    public function comments(): HasMany { return $this->hasMany(CommunityComment::class); }
    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'community_post_likes')->withTimestamps();
    }
}
