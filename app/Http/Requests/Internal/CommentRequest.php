<?php

declare(strict_types=1);

namespace App\Http\Requests\Internal;

use App\DTOs\CommentRequestDTO;
use Illuminate\Foundation\Http\FormRequest;

final class CommentRequest extends FormRequest
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
            'post_id' => ['required', 'integer', 'min:1'],
            'author_id' => ['required', 'integer', 'min:1'],
            'text' => ['required', 'string'],
            'parent_id' => ['nullable', 'integer', 'min:1'],
            'likes_count' => ['required', 'integer', 'min:0'],
            'replies_count' => ['required', 'integer', 'min:0'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'post_id' => ['description' => 'The id of the post this comment belongs to. Must already be synced.', 'example' => 1001],
            'author_id' => ['description' => "The comment author's id, as used by the calling backend.", 'example' => 42],
            'text' => ['description' => 'The comment text.', 'example' => 'Nice post!'],
            'parent_id' => ['description' => "If this is a reply, the parent comment's id.", 'example' => null],
            'likes_count' => ['description' => 'Current like count, as tracked by the calling backend.', 'example' => 0],
            'replies_count' => ['description' => 'Current reply count, as tracked by the calling backend.', 'example' => 0],
        ];
    }

    public function toDto(int $id): CommentRequestDTO
    {
        return new CommentRequestDTO(
            id: $id,
            postId: $this->integer('post_id'),
            authorId: $this->integer('author_id'),
            text: $this->string('text')->toString(),
            parentId: $this->filled('parent_id') ? $this->integer('parent_id') : null,
            likesCount: $this->integer('likes_count'),
            repliesCount: $this->integer('replies_count'),
        );
    }
}
