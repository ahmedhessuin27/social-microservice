<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Enums\PostStatus;
use App\Enums\PostType;

final readonly class PostRequestDTO
{
    /**
     * @param  array<int, array{url: string, position?: int}>  $media
     * @param  array<int, string>  $hashtags
     */
    public function __construct(
        public int $authorId,
        public ?string $content,
        public PostType $type,
        public PostStatus $status,
        public ?int $repostedFromId,
        public array $media = [],
        public array $hashtags = [],
    ) {}
}
