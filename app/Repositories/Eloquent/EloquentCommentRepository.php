<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\DTOs\CommentRequestDTO;
use App\Models\Comment;
use App\Repositories\Contracts\CommentRepositoryInterface;

class EloquentCommentRepository implements CommentRepositoryInterface
{
    public function upsert(CommentRequestDTO $data): Comment
    {
        return Comment::query()->updateOrCreate(
            ['id' => $data->id],
            [
                'post_id' => $data->postId,
                'author_id' => $data->authorId,
                'text' => $data->text,
                'parent_id' => $data->parentId,
                'likes_count' => $data->likesCount,
                'replies_count' => $data->repliesCount,
            ],
        );
    }

    public function delete(string $id): bool
    {
        return Comment::whereKey($id)->delete() > 0;
    }
}
