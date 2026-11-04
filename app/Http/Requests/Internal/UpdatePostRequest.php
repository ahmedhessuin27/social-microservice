<?php

declare(strict_types=1);

namespace App\Http\Requests\Internal;

use App\DTOs\UpdatePostRequestDTO;
use App\Enums\PostStatus;
use App\Enums\PostType;
use Illuminate\Validation\Rule;

final class UpdatePostRequest extends ActorRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'content' => ['sometimes', 'nullable', 'string'],
            'type' => ['sometimes', Rule::enum(PostType::class)],
            'status' => ['sometimes', Rule::enum(PostStatus::class)],
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
            'user_id' => ['description' => "The id of the user editing — must be the post's author.", 'example' => 42],
            'content' => ['description' => 'New post text. Omit to keep the current text.', 'example' => 'Hello again'],
            'type' => ['description' => 'One of: text, image, video, multi_image. Omit to keep the current type.', 'example' => 'text'],
            'status' => ['description' => 'One of: published, draft, hidden. Omit to keep the current status.', 'example' => 'published'],
            'media' => ['description' => 'Replaces all of the post\'s media. Omit to keep the current media; send [] to remove it.'],
            'media.*.url' => ['description' => 'A publicly resolvable media URL.', 'example' => 'https://example.com/media/1.jpg'],
            'media.*.position' => ['description' => 'Display order within the post, 0-indexed.', 'example' => 0],
            'hashtags' => ['description' => 'Replaces all of the post\'s hashtags. Omit to keep the current ones.'],
            'hashtags.*' => ['description' => 'A hashtag name, without the leading #.', 'example' => 'laravel'],
        ];
    }

    public function toDto(): UpdatePostRequestDTO
    {
        $attributes = $this->safe()->only(['content', 'type', 'status']);

        if (isset($attributes['type'])) {
            $attributes['type'] = PostType::from($attributes['type']);
        }

        if (isset($attributes['status'])) {
            $attributes['status'] = PostStatus::from($attributes['status']);
        }

        return new UpdatePostRequestDTO(
            attributes: $attributes,
            media: $this->has('media') ? $this->array('media') : null,
            hashtags: $this->has('hashtags') ? $this->array('hashtags') : null,
        );
    }
}
