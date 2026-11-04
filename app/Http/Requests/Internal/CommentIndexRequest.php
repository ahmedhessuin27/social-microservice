<?php

declare(strict_types=1);

namespace App\Http\Requests\Internal;

final class CommentIndexRequest extends FeedRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'parent_id' => ['sometimes', 'integer', 'min:1'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function queryParameters(): array
    {
        return [
            ...parent::queryParameters(),
            'parent_id' => ['description' => "List the replies to this comment instead of the post's top-level comments.", 'example' => null],
        ];
    }

    public function parentId(): ?int
    {
        return $this->filled('parent_id') ? $this->integer('parent_id') : null;
    }
}
