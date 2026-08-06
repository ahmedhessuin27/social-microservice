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
            'follower_id' => ['required', 'uuid'],
            'following_id' => ['required', 'uuid', 'different:follower_id'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'follower_id' => ['description' => "The following user's id, as used by the calling backend.", 'example' => '0199c2b1-1a1a-7000-8b3a-1a2b3c4d5e6f'],
            'following_id' => ['description' => "The followed user's id, as used by the calling backend.", 'example' => '0199c2b1-5c5c-7000-8b3a-1a2b3c4d5e6f'],
        ];
    }

    public function toDto(): FollowRequestDTO
    {
        return new FollowRequestDTO(
            followerId: $this->string('follower_id')->toString(),
            followingId: $this->string('following_id')->toString(),
        );
    }
}
