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
            'user_id' => ['required', 'uuid'],
            'likeable_id' => ['required', 'uuid'],
            'likeable_type' => ['required', Rule::in(['post', 'comment'])],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'user_id' => ['description' => "The liking user's id, as used by the calling backend.", 'example' => '0199c2b1-1a1a-7000-8b3a-1a2b3c4d5e6f'],
            'likeable_id' => ['description' => 'The id of the post or comment being liked. Must already be synced.', 'example' => '0199c2b1-2f2b-7000-8b3a-1a2b3c4d5e6f'],
            'likeable_type' => ['description' => 'Either "post" or "comment".', 'example' => 'post'],
        ];
    }

    public function toDto(string $id): LikeRequestDTO
    {
        return new LikeRequestDTO(
            id: $id,
            userId: $this->string('user_id')->toString(),
            likeableId: $this->string('likeable_id')->toString(),
            likeableType: $this->string('likeable_type')->toString(),
        );
    }
}
