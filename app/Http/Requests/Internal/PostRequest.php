<?php

declare(strict_types=1);

namespace App\Http\Requests\Internal;

use App\DTOs\PostRequestDTO;
use App\Enums\PostStatus;
use App\Enums\PostType;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class PostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'author_id' => ['required', 'uuid'],
            'content' => ['nullable', 'string'],
            'type' => ['required', Rule::enum(PostType::class)],
            'status' => ['required', Rule::enum(PostStatus::class)],
            'likes_count' => ['required', 'integer', 'min:0'],
            'comments_count' => ['required', 'integer', 'min:0'],
            'reposts_count' => ['required', 'integer', 'min:0'],
            'reposted_from_id' => ['nullable', 'uuid'],
            'posted_at' => ['required', 'date'],
            'media' => ['sometimes', 'array'],
            'media.*.url' => ['required', 'url'],
            'media.*.position' => ['sometimes', 'integer', 'min:0'],
            'hashtags' => ['sometimes', 'array'],
            'hashtags.*' => ['string', 'max:255'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'author_id' => ['description' => "The post author's id, as used by the calling backend.", 'example' => '0199c2b1-1a1a-7000-8b3a-1a2b3c4d5e6f'],
            'content' => ['description' => 'The post text.', 'example' => 'Hello world'],
            'type' => ['description' => 'One of: text, image, video, multi_image.', 'example' => 'text'],
            'status' => ['description' => 'One of: published, draft, hidden. Only published posts appear in feeds.', 'example' => 'published'],
            'likes_count' => ['description' => 'Current like count, as tracked by the calling backend.', 'example' => 3],
            'comments_count' => ['description' => 'Current comment count, as tracked by the calling backend.', 'example' => 1],
            'reposts_count' => ['description' => 'Current repost count, as tracked by the calling backend.', 'example' => 0],
            'reposted_from_id' => ['description' => 'If this is a repost, the id of the original post (must already be synced).', 'example' => null],
            'posted_at' => ['description' => "The post's original creation time in the calling backend — used for feed ordering.", 'example' => '2026-08-06T12:00:00Z'],
            'media' => ['description' => 'Attached media, replacing whatever was previously stored for this post.'],
            'media.*.url' => ['description' => 'A publicly resolvable media URL.', 'example' => 'https://example.com/media/1.jpg'],
            'media.*.position' => ['description' => 'Display order within the post, 0-indexed.', 'example' => 0],
            'hashtags' => ['description' => 'Hashtag names, replacing whatever was previously stored for this post.'],
            'hashtags.*' => ['description' => 'A hashtag name, without the leading #.', 'example' => 'laravel'],
        ];
    }

    public function toDto(string $id): PostRequestDTO
    {
        return new PostRequestDTO(
            id: $id,
            authorId: $this->string('author_id')->toString(),
            content: $this->input('content'),
            type: PostType::from($this->string('type')->toString()),
            status: PostStatus::from($this->string('status')->toString()),
            likesCount: $this->integer('likes_count'),
            commentsCount: $this->integer('comments_count'),
            repostsCount: $this->integer('reposts_count'),
            repostedFromId: $this->input('reposted_from_id'),
            postedAt: CarbonImmutable::parse($this->string('posted_at')->toString()),
            media: $this->array('media'),
            hashtags: $this->array('hashtags'),
        );
    }
}
