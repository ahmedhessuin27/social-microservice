<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Services\FeedService;
use Illuminate\Container\Attributes\Bind;
use Illuminate\Contracts\Pagination\CursorPaginator;

#[Bind(FeedService::class)]
interface FeedServiceInterface
{
    /**
     * Every author's published posts — for consumers with no follow graph.
     */
    public function latest(int $perPage, ?int $viewerId = null): CursorPaginator;

    public function forUser(int $userId, int $perPage, ?int $viewerId = null): CursorPaginator;

    /**
     * One author's published posts, e.g. for a profile page.
     */
    public function byAuthor(int $authorId, int $perPage, ?int $viewerId = null): CursorPaginator;
}
