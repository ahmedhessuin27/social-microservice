<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\DTOs\FollowRequestDTO;
use App\Models\Follow;
use App\Repositories\Eloquent\EloquentFollowRepository;
use Illuminate\Container\Attributes\Bind;

#[Bind(EloquentFollowRepository::class)]
interface FollowRepositoryInterface
{
    /**
     * Insert the follow relationship if it doesn't exist, or return the
     * existing one — makes PUT /internal/follows idempotent.
     */
    public function upsert(FollowRequestDTO $data): Follow;

    public function delete(string $followerId, string $followingId): bool;

    /**
     * @return array<int, string> ids of the users the given user follows
     */
    public function followingIds(string $userId): array;
}
