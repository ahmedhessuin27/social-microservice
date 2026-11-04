<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\DTOs\CommentRequestDTO;
use App\Models\Comment;
use App\Models\Post;
use App\Repositories\Eloquent\EloquentCommentRepository;
use Illuminate\Container\Attributes\Bind;
use Illuminate\Contracts\Pagination\CursorPaginator;

#[Bind(EloquentCommentRepository::class)]
interface CommentRepositoryInterface
{
    public function create(Post $post, CommentRequestDTO $data): Comment;

    public function update(Comment $comment, string $text): Comment;

    /**
     * Deletes the comment together with its replies.
     */
    public function delete(Comment $comment): void;

    /**
     * A post's top-level comments, or the replies to one of them, oldest first.
     */
    public function forPost(Post $post, ?int $parentId, int $perPage, ?int $viewerId = null): CursorPaginator;
}
