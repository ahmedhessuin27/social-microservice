<?php

declare(strict_types=1);

namespace App\DTOs;

final readonly class LikeRequestDTO
{
    public function __construct(
        public int $id,
        public int $userId,
        public int $likeableId,
        public string $likeableType,
    ) {}
}
