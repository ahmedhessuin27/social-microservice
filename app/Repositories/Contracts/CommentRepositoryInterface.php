<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\DTOs\CommentRequestDTO;
use App\Models\Comment;
use App\Repositories\Eloquent\EloquentCommentRepository;
use Illuminate\Container\Attributes\Bind;

#[Bind(EloquentCommentRepository::class)]
interface CommentRepositoryInterface
{
    /**
     * Insert the comment if it doesn't exist, or update it if it does —
     * makes PUT /internal/comments/{id} idempotent.
     */
    public function upsert(CommentRequestDTO $data): Comment;

    public function delete(string $id): bool;
}
