<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\DTOs\PostRequestDTO;
use App\Models\Post;
use App\Repositories\Eloquent\EloquentPostRepository;
use Illuminate\Container\Attributes\Bind;
use Illuminate\Contracts\Pagination\CursorPaginator;

#[Bind(EloquentPostRepository::class)]
interface PostRepositoryInterface
{
    public function upsert(PostRequestDTO $data): Post;

    public function delete(int $id): bool;

    /**
     * Posts by the given authors, published, newest first.
     *
     * @param  array<int, int>  $authorIds
     */
    public function feedForAuthors(array $authorIds, int $perPage): CursorPaginator;

    /**
     * Every author's posts, published, newest first.
     */
    public function latestPublished(int $perPage): CursorPaginator;
}
