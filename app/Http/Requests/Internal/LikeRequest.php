<?php

declare(strict_types=1);

namespace App\Http\Requests\Internal;

use App\DTOs\LikeRequestDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class LikeRequest extends FormRequest
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
            'likeable_id' => ['required', 'integer', 'min:1'],
            'likeable_type' => ['required', Rule::in(['post', 'comment'])],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'user_id' => ['description' => "The liking user's id, as used by the calling backend.", 'example' => 42],
            'likeable_id' => ['description' => 'The id of the post or comment being liked. Must already be synced.', 'example' => 1001],
            'likeable_type' => ['description' => 'Either "post" or "comment".', 'example' => 'post'],
        ];
    }

    public function toDto(int $id): LikeRequestDTO
    {
        return new LikeRequestDTO(
            id: $id,
            userId: $this->integer('user_id'),
            likeableId: $this->integer('likeable_id'),
            likeableType: $this->string('likeable_type')->toString(),
        );
    }
}
