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

    public function latest(int $perPage, ?int $viewerId = null): CursorPaginator
    {
        return $this->posts->latestPublished($perPage, $viewerId);
    }

    public function forUser(int $userId, int $perPage, ?int $viewerId = null): CursorPaginator
    {
        $authorIds = [$userId, ...$this->follows->followingIds($userId)];

        return $this->posts->feedForAuthors($authorIds, $perPage, $viewerId ?? $userId);
    }

    public function byAuthor(int $authorId, int $perPage, ?int $viewerId = null): CursorPaginator
    {
        return $this->posts->feedForAuthors([$authorId], $perPage, $viewerId);
    }
}
