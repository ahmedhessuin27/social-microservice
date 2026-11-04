<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PostStatus;
use App\Enums\PostType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    // Primary key mirrors the consuming application's own integer id.
    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = [
        'id',
        'author_id',
        'content',
        'type',
        'status',
        'likes_count',
        'comments_count',
        'reposts_count',
        'reposted_from_id',
        'posted_at',
    ];

    protected function casts(): array
    {
        return [
            'type' => PostType::class,
            'status' => PostStatus::class,
            'likes_count' => 'integer',
            'comments_count' => 'integer',
            'reposts_count' => 'integer',
            'posted_at' => 'datetime',
        ];
    }

    public function media(): HasMany
    {
        return $this->hasMany(PostMedia::class)->orderBy('position');
    }

    public function hashtags(): BelongsToMany
    {
        return $this->belongsToMany(Hashtag::class, 'post_hashtags')
            ->using(PostHashtag::class)
            ->withTimestamps();
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function repostedFrom(): BelongsTo
    {
        return $this->belongsTo(self::class, 'reposted_from_id');
    }

    public function reposts(): HasMany
    {
        return $this->hasMany(self::class, 'reposted_from_id');
    }
}
