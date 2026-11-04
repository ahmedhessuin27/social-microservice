<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\Contracts\FollowRepositoryInterface;
use App\Repositories\Contracts\PostRepositoryInterface;
use App\Services\Contracts\FeedServiceInterface;
use Illuminate\Contracts\Pagination\CursorPaginator;

final readonly class FeedService implements FeedServiceInterface
{
    public function __construct(
        private PostRepositoryInterface $posts,
        private FollowRepositoryInterface $follows,
    ) {}

    public function latest(int $perPage): CursorPaginator
    {
        return $this->posts->latestPublished($perPage);
    }

    public function forUser(int $userId, int $perPage): CursorPaginator
    {
        $authorIds = [$userId, ...$this->follows->followingIds($userId)];

        return $this->posts->feedForAuthors($authorIds, $perPage);
    }
}
