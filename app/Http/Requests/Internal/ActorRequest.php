<?php

declare(strict_types=1);

namespace App\Http\Requests\Internal;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Write requests on an existing post/comment: who is acting. The calling
 * backend sends its logged-in user's id; this service checks ownership.
 */
class ActorRequest extends FormRequest
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
            'user_id' => ['required', 'integer', 'min:1'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'user_id' => ['description' => 'The id of the user performing the action, as used by the calling backend.', 'example' => 42],
        ];
    }

    public function userId(): int
    {
        return $this->integer('user_id');
    }
}
