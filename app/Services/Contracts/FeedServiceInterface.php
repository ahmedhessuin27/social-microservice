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
    public function latest(int $perPage): CursorPaginator;

    public function forUser(int $userId, int $perPage): CursorPaginator;
}
