<?php

declare(strict_types=1);

namespace App\Http\Requests\Internal;

use App\DTOs\CommentRequestDTO;
use App\Models\Post;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        /** @var Post $post */
        $post = $this->route('post');

        return [
            'author_id' => ['required', 'integer', 'min:1'],
            'text' => ['required', 'string'],
            // Replies are one level deep: the parent must be a top-level
            // comment on this same post.
            'parent_id' => ['nullable', 'integer', Rule::exists('comments', 'id')
                ->where(fn (Builder $query) => $query->where('post_id', $post->getKey())->whereNull('parent_id'))
                ->withoutTrashed()],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'author_id' => ['description' => "The comment author's id, as used by the calling backend.", 'example' => 42],
            'text' => ['description' => 'The comment text.', 'example' => 'Nice post!'],
            'parent_id' => ['description' => 'To reply to a comment, its id. Must be a top-level comment on the same post.', 'example' => null],
        ];
    }

    public function toDto(): CommentRequestDTO
    {
        return new CommentRequestDTO(
            authorId: $this->integer('author_id'),
            text: $this->string('text')->toString(),
            parentId: $this->filled('parent_id') ? $this->integer('parent_id') : null,
        );
    }
}
