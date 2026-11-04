<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'author_id' => $this->author_id,
            'content' => $this->content,
            'type' => $this->type,
            'status' => $this->status,
            'likes_count' => $this->likes_count,
            'comments_count' => $this->comments_count,
            'reposts_count' => $this->reposts_count,
            'reposted_from_id' => $this->reposted_from_id,
            // Only present when the request named a viewer_id.
            'liked' => $this->when(array_key_exists('liked', $this->resource->getAttributes()), fn (): bool => (bool) $this->liked),
            'posted_at' => $this->posted_at,
            'updated_at' => $this->updated_at,
            'media' => $this->whenLoaded('media', fn () => $this->media->map(fn ($item) => [
                'url' => $item->url,
                'position' => $item->position,
            ])),
            'hashtags' => $this->whenLoaded('hashtags', fn () => $this->hashtags->pluck('name')),
        ];
    }
}
