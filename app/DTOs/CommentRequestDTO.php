<?php

declare(strict_types=1);

namespace App\DTOs;

final readonly class CommentRequestDTO
{
    public function __construct(
        public int $authorId,
        public string $text,
        public ?int $parentId,
    ) {}
}
