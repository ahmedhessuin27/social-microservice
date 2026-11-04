<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Comment;
use App\Models\Post;
use App\Repositories\Eloquent\EloquentLikeRepository;
use Illuminate\Container\Attributes\Bind;

#[Bind(EloquentLikeRepository::class)]
interface LikeRepositoryInterface
{
    /**
     * Idempotent: liking twice counts once.
     *
     * @return int the likeable's likes_count afterwards
     */
    public function like(Post|Comment $likeable, int $userId): int;

    /**
     * Idempotent: unliking something not liked changes nothing.
     *
     * @return int the likeable's likes_count afterwards
     */
    public function unlike(Post|Comment $likeable, int $userId): int;
}
