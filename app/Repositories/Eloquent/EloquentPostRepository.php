<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\DTOs\PostRequestDTO;
use App\DTOs\UpdatePostRequestDTO;
use App\Enums\PostStatus;
use App\Models\Hashtag;
use App\Models\Post;
use App\Repositories\Contracts\PostRepositoryInterface;
use App\Repositories\Eloquent\Concerns\AdjustsCounters;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class EloquentPostRepository implements PostRepositoryInterface
{
    use AdjustsCounters;

    public function create(PostRequestDTO $data): Post
    {
        $post = DB::transaction(function () use ($data): Post {
            $post = Post::query()->create([
                'author_id' => $data->authorId,
                'content' => $data->content,
                'type' => $data->type,
                'status' => $data->status,
                'reposted_from_id' => $data->repostedFromId,
                'posted_at' => now(),
            ]);

            if ($data->repostedFromId !== null) {
                $this->adjustCounter($post->repostedFrom, 'reposts_count', 1);
            }

            $this->replaceMedia($post, $data->media);
            $this->replaceHashtags($post, $data->hashtags);

            return $post;
        });

        return $this->loadForViewer($post->refresh(), $data->authorId);
    }

    public function update(Post $post, UpdatePostRequestDTO $data): Post
    {
        DB::transaction(function () use ($post, $data): void {
            $post->fill($data->attributes);

            // Publishing a draft puts it at the top of feeds, like a new post.
            if ($post->isDirty('status') && $post->status === PostStatus::Published) {
                $post->posted_at = now();
            }

            $post->save();

            if ($data->media !== null) {
                $this->replaceMedia($post, $data->media);
            }

            if ($data->hashtags !== null) {
                $this->replaceHashtags($post, $data->hashtags);
            }
        });

        return $this->loadForViewer($post->refresh(), $post->author_id);
    }

    public function delete(Post $post): void
    {
        DB::transaction(function () use ($post): void {
            if ($post->repostedFrom !== null) {
                $this->adjustCounter($post->repostedFrom, 'reposts_count', -1);
            }

            $post->delete();
        });
    }

    public function loadForViewer(Post $post, ?int $viewerId): Post
    {
        $post->load(['media', 'hashtags']);

        if ($viewerId !== null) {
            $post->loadExists(['likes as liked' => fn (Builder $query) => $query->where('user_id', $viewerId)]);
        }

        return $post;
    }

    public function feedForAuthors(array $authorIds, int $perPage, ?int $viewerId = null): CursorPaginator
    {
        return $this->publishedNewestFirst($viewerId)
            ->whereIn('author_id', $authorIds)
            ->cursorPaginate($perPage);
    }

    public function latestPublished(int $perPage, ?int $viewerId = null): CursorPaginator
    {
        return $this->publishedNewestFirst($viewerId)->cursorPaginate($perPage);
    }

    /**
     * @return Builder<Post>
     */
    private function publishedNewestFirst(?int $viewerId): Builder
    {
        return Post::query()
            ->where('status', PostStatus::Published)
            ->with(['media', 'hashtags'])
            ->when($viewerId !== null, fn (Builder $query) => $query->withExists([
                'likes as liked' => fn (Builder $likes) => $likes->where('user_id', $viewerId),
            ]))
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
