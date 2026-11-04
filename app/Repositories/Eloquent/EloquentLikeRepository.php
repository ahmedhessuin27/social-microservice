<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Repositories\Contracts\LikeRepositoryInterface;
use App\Repositories\Eloquent\Concerns\AdjustsCounters;
use Illuminate\Support\Facades\DB;

class EloquentLikeRepository implements LikeRepositoryInterface
{
    use AdjustsCounters;

    public function like(Post|Comment $likeable, int $userId): int
    {
        return DB::transaction(function () use ($likeable, $userId): int {
            // insertOrIgnore + the (user_id, likeable) unique index: a
            // double-tap or a concurrent duplicate inserts nothing, so the
            // counter only moves when a like was really added.
            $inserted = Like::query()->insertOrIgnore([
                'user_id' => $userId,
                'likeable_id' => $likeable->getKey(),
                'likeable_type' => $likeable->getMorphClass(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($inserted > 0) {
                $this->adjustCounter($likeable, 'likes_count', 1);
            }

            return $this->likesCount($likeable);
        });
    }

    public function unlike(Post|Comment $likeable, int $userId): int
    {
        return DB::transaction(function () use ($likeable, $userId): int {
            $deleted = $likeable->likes()->where('user_id', $userId)->delete();

            if ($deleted > 0) {
                $this->adjustCounter($likeable, 'likes_count', -1);
            }

            return $this->likesCount($likeable);
        });
    }

    private function likesCount(Post|Comment $likeable): int
    {
        return (int) $likeable->newQueryWithoutScopes()->whereKey($likeable->getKey())->value('likes_count');
    }
}
