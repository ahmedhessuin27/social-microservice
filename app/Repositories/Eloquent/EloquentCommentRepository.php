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
        // withTrashed: a re-sync of a previously deleted id must revive
        // that row, not insert a second one under the same primary key.
        $comment = Comment::withTrashed()->updateOrCreate(
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

        if ($comment->trashed()) {
            $comment->restore();
        }

        return $comment;
    }

    public function delete(int $id): bool
    {
        return Comment::whereKey($id)->delete() > 0;
    }
}
