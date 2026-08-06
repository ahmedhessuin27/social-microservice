<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Enums\PostStatus;
use App\Enums\PostType;
use Carbon\CarbonImmutable;

final readonly class PostRequestDTO
{
    /**
     * @param  array<int, array{url: string, position?: int}>  $media
     * @param  array<int, string>  $hashtags
     */
    public function __construct(
        public string $id,
        public string $authorId,
        public ?string $content,
        public PostType $type,
        public PostStatus $status,
        public int $likesCount,
        public int $commentsCount,
        public int $repostsCount,
        public ?string $repostedFromId,
        public CarbonImmutable $postedAt,
        public array $media = [],
        public array $hashtags = [],
    ) {}
}
