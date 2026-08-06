<?php

declare(strict_types=1);

namespace App\DTOs;

final readonly class CommentRequestDTO
{
    public function __construct(
        public string $id,
        public string $postId,
        public string $authorId,
        public string $text,
        public ?string $parentId,
        public int $likesCount,
        public int $repliesCount,
    ) {}
}
