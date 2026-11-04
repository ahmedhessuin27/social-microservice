<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\DTOs\CommentRequestDTO;
use App\Models\Comment;
use App\Models\Post;
use App\Repositories\Contracts\CommentRepositoryInterface;
use App\Repositories\Eloquent\Concerns\AdjustsCounters;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class EloquentCommentRepository implements CommentRepositoryInterface
{
    use AdjustsCounters;

    public function create(Post $post, CommentRequestDTO $data): Comment
    {
        return DB::transaction(function () use ($post, $data): Comment {
            $comment = $post->comments()->create([
                'author_id' => $data->authorId,
                'text' => $data->text,
                'parent_id' => $data->parentId,
            ]);

            // comments_count counts replies too: it's the post's total.
            $this->adjustCounter($post, 'comments_count', 1);

            if ($comment->parent !== null) {
                $this->adjustCounter($comment->parent, 'replies_count', 1);
            }

            return $comment->refresh();
        });
    }

    public function update(Comment $comment, string $text): Comment
    {
        $comment->update(['text' => $text]);

        return $comment;
    }

    public function delete(Comment $comment): void
    {
        DB::transaction(function () use ($comment): void {
            $deletedReplies = $comment->replies()->delete();
            $comment->delete();

            $this->adjustCounter($comment->post, 'comments_count', -(1 + $deletedReplies));

            if ($comment->parent !== null) {
                $this->adjustCounter($comment->parent, 'replies_count', -1);
            }
        });
    }

    public function forPost(Post $post, ?int $parentId, int $perPage, ?int $viewerId = null): CursorPaginator
    {
        return $post->comments()
            ->where('parent_id', $parentId)
            ->when($viewerId !== null, fn (Builder $query) => $query->withExists([
                'likes as liked' => fn (Builder $likes) => $likes->where('user_id', $viewerId),
            ]))
            ->orderBy('created_at')
            ->orderBy('id')
            ->cursorPaginate($perPage);
    }
}
