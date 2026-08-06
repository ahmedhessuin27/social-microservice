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
            'post_id' => ['required', 'uuid'],
            'author_id' => ['required', 'uuid'],
            'text' => ['required', 'string'],
            'parent_id' => ['nullable', 'uuid'],
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
            'post_id' => ['description' => 'The id of the post this comment belongs to. Must already be synced.', 'example' => '0199c2b1-2f2b-7000-8b3a-1a2b3c4d5e6f'],
            'author_id' => ['description' => "The comment author's id, as used by the calling backend.", 'example' => '0199c2b1-1a1a-7000-8b3a-1a2b3c4d5e6f'],
            'text' => ['description' => 'The comment text.', 'example' => 'Nice post!'],
            'parent_id' => ['description' => "If this is a reply, the parent comment's id.", 'example' => null],
            'likes_count' => ['description' => 'Current like count, as tracked by the calling backend.', 'example' => 0],
            'replies_count' => ['description' => 'Current reply count, as tracked by the calling backend.', 'example' => 0],
        ];
    }

    public function toDto(string $id): CommentRequestDTO
    {
        return new CommentRequestDTO(
            id: $id,
            postId: $this->string('post_id')->toString(),
            authorId: $this->string('author_id')->toString(),
            text: $this->string('text')->toString(),
            parentId: $this->input('parent_id'),
            likesCount: $this->integer('likes_count'),
            repliesCount: $this->integer('replies_count'),
        );
    }
}
