<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\DTOs\FollowRequestDTO;
use App\Models\Follow;
use App\Repositories\Contracts\FollowRepositoryInterface;

class EloquentFollowRepository implements FollowRepositoryInterface
{
    public function upsert(FollowRequestDTO $data): Follow
    {
        return Follow::query()->firstOrCreate([
            'follower_id' => $data->followerId,
            'following_id' => $data->followingId,
        ]);
    }

    public function delete(int $followerId, int $followingId): bool
    {
        return Follow::where('follower_id', $followerId)
            ->where('following_id', $followingId)
            ->delete() > 0;
    }

    public function followingIds(int $userId): array
    {
        return Follow::where('follower_id', $userId)->pluck('following_id')->all();
    }
}
