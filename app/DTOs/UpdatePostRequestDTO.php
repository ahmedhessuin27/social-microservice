<?php

declare(strict_types=1);

namespace App\DTOs;

final readonly class UpdatePostRequestDTO
{
    /**
     * Only what the caller actually sent: an absent field leaves that part
     * of the post unchanged.
     *
     * @param  array<string, mixed>  $attributes  post columns to change (content, type, status)
     * @param  array<int, array{url: string, position?: int}>|null  $media  null = keep current media
     * @param  array<int, string>|null  $hashtags  null = keep current hashtags
     */
    public function __construct(
        public array $attributes,
        public ?array $media,
        public ?array $hashtags,
    ) {}
}
