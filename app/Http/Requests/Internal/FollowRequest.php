<?php

declare(strict_types=1);

namespace App\Http\Requests\Internal;

use App\DTOs\FollowRequestDTO;
use Illuminate\Foundation\Http\FormRequest;

final class FollowRequest extends FormRequest
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
            'follower_id' => ['required', 'integer', 'min:1'],
            'following_id' => ['required', 'integer', 'min:1', 'different:follower_id'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'follower_id' => ['description' => "The following user's id, as used by the calling backend.", 'example' => 42],
            'following_id' => ['description' => "The followed user's id, as used by the calling backend.", 'example' => 43],
        ];
    }

    public function toDto(): FollowRequestDTO
    {
        return new FollowRequestDTO(
            followerId: $this->integer('follower_id'),
            followingId: $this->integer('following_id'),
        );
    }
}
