<?php

declare(strict_types=1);

namespace App\Http\Requests\Internal;

use App\DTOs\PostRequestDTO;
use App\Enums\PostStatus;
use App\Enums\PostType;
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
            'author_id' => ['required', 'integer', 'min:1'],
            'content' => ['nullable', 'string', 'required_without:media'],
            'type' => ['required', Rule::enum(PostType::class)],
            'status' => ['sometimes', Rule::enum(PostStatus::class)],
            'reposted_from_id' => ['nullable', 'integer', Rule::exists('posts', 'id')->withoutTrashed()],
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
            'author_id' => ['description' => "The post author's id, as used by the calling backend.", 'example' => 42],
            'content' => ['description' => 'The post text. Required unless media is sent.', 'example' => 'Hello world'],
            'type' => ['description' => 'One of: text, image, video, multi_image.', 'example' => 'text'],
            'status' => ['description' => 'One of: published, draft, hidden. Defaults to published. Only published posts appear in feeds.', 'example' => 'published'],
            'reposted_from_id' => ['description' => 'If this is a repost, the id of the original post.', 'example' => null],
            'media' => ['description' => 'Attached media.'],
            'media.*.url' => ['description' => 'A publicly resolvable media URL.', 'example' => 'https://example.com/media/1.jpg'],
            'media.*.position' => ['description' => 'Display order within the post, 0-indexed.', 'example' => 0],
            'hashtags' => ['description' => 'Hashtag names.'],
            'hashtags.*' => ['description' => 'A hashtag name, without the leading #.', 'example' => 'laravel'],
        ];
    }

    public function toDto(): PostRequestDTO
    {
        return new PostRequestDTO(
            authorId: $this->integer('author_id'),
            content: $this->input('content'),
            type: PostType::from($this->string('type')->toString()),
            status: PostStatus::from($this->string('status', PostStatus::Published->value)->toString()),
            repostedFromId: $this->filled('reposted_from_id') ? $this->integer('reposted_from_id') : null,
            media: $this->array('media'),
            hashtags: $this->array('hashtags'),
        );
    }
}
