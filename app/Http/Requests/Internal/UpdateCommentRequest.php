<?php

declare(strict_types=1);

namespace App\Http\Requests\Internal;

final class UpdateCommentRequest extends ActorRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'text' => ['required', 'string'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'user_id' => ['description' => "The id of the user editing — must be the comment's author.", 'example' => 42],
            'text' => ['description' => 'The new comment text.', 'example' => 'Nice post (edited)'],
        ];
    }

    public function text(): string
    {
        return $this->string('text')->toString();
    }
}
