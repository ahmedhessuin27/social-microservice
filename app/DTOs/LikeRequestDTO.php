<?php

declare(strict_types=1);

namespace App\DTOs;

final readonly class LikeRequestDTO
{
    public function __construct(
        public string $id,
        public string $userId,
        public string $likeableId,
        public string $likeableType,
    ) {}
}
