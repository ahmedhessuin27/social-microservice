<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\DTOs\PostRequestDTO;
use App\DTOs\UpdatePostRequestDTO;
use App\Models\Post;
use App\Repositories\Eloquent\EloquentPostRepository;
use Illuminate\Container\Attributes\Bind;
use Illuminate\Contracts\Pagination\CursorPaginator;

#[Bind(EloquentPostRepository::class)]
interface PostRepositoryInterface
{
    public function create(PostRequestDTO $data): Post;

    public function update(Post $post, UpdatePostRequestDTO $data): Post;

    public function delete(Post $post): void;

    /**
     * Load what a single-post response shows: media, hashtags and, given a
     * viewer, whether they liked it.
     */
    public function loadForViewer(Post $post, ?int $viewerId): Post;

    /**
     * Posts by the given authors, published, newest first.
     *
     * @param  array<int, int>  $authorIds
     */
    public function feedForAuthors(array $authorIds, int $perPage, ?int $viewerId = null): CursorPaginator;

    /**
     * Every author's posts, published, newest first.
     */
    public function latestPublished(int $perPage, ?int $viewerId = null): CursorPaginator;
}
