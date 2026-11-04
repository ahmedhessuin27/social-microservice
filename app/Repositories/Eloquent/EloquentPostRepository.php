<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\DTOs\PostRequestDTO;
use App\Enums\PostStatus;
use App\Models\Hashtag;
use App\Models\Post;
use App\Repositories\Contracts\PostRepositoryInterface;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class EloquentPostRepository implements PostRepositoryInterface
{
    public function upsert(PostRequestDTO $data): Post
    {
        return DB::transaction(function () use ($data): Post {
            // withTrashed: a re-sync of a previously deleted id must revive
            // that row, not insert a second one under the same primary key.
            $post = Post::withTrashed()->updateOrCreate(
                ['id' => $data->id],
                [
                    'author_id' => $data->authorId,
                    'content' => $data->content,
                    'type' => $data->type,
                    'status' => $data->status,
                    'likes_count' => $data->likesCount,
                    'comments_count' => $data->commentsCount,
                    'reposts_count' => $data->repostsCount,
                    'reposted_from_id' => $data->repostedFromId,
                    'posted_at' => $data->postedAt,
                ],
            );

            if ($post->trashed()) {
                $post->restore();
            }

            $this->replaceMedia($post, $data->media);
            $this->replaceHashtags($post, $data->hashtags);

            return $post;
        });
    }

    public function delete(int $id): bool
    {
        return Post::whereKey($id)->delete() > 0;
    }

    public function feedForAuthors(array $authorIds, int $perPage): CursorPaginator
    {
        return $this->publishedNewestFirst()
            ->whereIn('author_id', $authorIds)
            ->cursorPaginate($perPage);
    }

    public function latestPublished(int $perPage): CursorPaginator
    {
        return $this->publishedNewestFirst()->cursorPaginate($perPage);
    }

    /**
     * @return Builder<Post>
     */
    private function publishedNewestFirst(): Builder
    {
        return Post::query()
            ->where('status', PostStatus::Published)
            ->with(['media', 'hashtags'])
            ->orderByDesc('posted_at')
            ->orderByDesc('id');
    }

    /**
     * @param  array<int, array{url: string, position?: int}>  $media
     */
    private function replaceMedia(Post $post, array $media): void
    {
        $post->media()->delete();

        if ($media === []) {
            return;
        }

        $post->media()->createMany(array_map(
            fn (array $item, int $index): array => [
                'url' => $item['url'],
                'position' => $item['position'] ?? $index,
            ],
            $media,
            array_keys($media),
        ));
    }

    /**
     * @param  array<int, string>  $hashtags
     */
    private function replaceHashtags(Post $post, array $hashtags): void
    {
        $ids = collect($hashtags)
            ->filter()
            ->unique()
            ->map(fn (string $name): string => Hashtag::query()->firstOrCreate(['name' => $name])->id)
            ->all();

        $post->hashtags()->sync($ids);
    }
}
