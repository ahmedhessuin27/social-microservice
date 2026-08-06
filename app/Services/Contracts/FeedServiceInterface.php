<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Services\FeedService;
use Illuminate\Container\Attributes\Bind;
use Illuminate\Contracts\Pagination\CursorPaginator;

#[Bind(FeedService::class)]
interface FeedServiceInterface
{
    public function forUser(string $userId, int $perPage): CursorPaginator;
}
