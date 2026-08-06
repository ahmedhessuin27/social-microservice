<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\DTOs\LikeRequestDTO;
use App\Models\Like;
use App\Repositories\Eloquent\EloquentLikeRepository;
use Illuminate\Container\Attributes\Bind;

#[Bind(EloquentLikeRepository::class)]
interface LikeRepositoryInterface
{
    /**
     * Insert the like if it doesn't exist, or update it if it does —
     * makes PUT /internal/likes/{id} idempotent.
     */
    public function upsert(LikeRequestDTO $data): Like;

    public function delete(string $id): bool;
}
