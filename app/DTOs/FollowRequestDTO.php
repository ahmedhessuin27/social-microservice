<?php

declare(strict_types=1);

namespace App\DTOs;

final readonly class FollowRequestDTO
{
    public function __construct(
        public int $followerId,
        public int $followingId,
    ) {}
}
