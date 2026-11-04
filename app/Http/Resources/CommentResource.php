<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'post_id' => $this->post_id,
            'author_id' => $this->author_id,
            'parent_id' => $this->parent_id,
            'text' => $this->text,
            'likes_count' => $this->likes_count,
            'replies_count' => $this->replies_count,
            // Only present when the request named a viewer_id.
            'liked' => $this->when(array_key_exists('liked', $this->resource->getAttributes()), fn (): bool => (bool) $this->liked),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
