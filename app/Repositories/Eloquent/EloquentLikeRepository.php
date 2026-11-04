<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\DTOs\LikeRequestDTO;
use App\Models\Like;
use App\Repositories\Contracts\LikeRepositoryInterface;

class EloquentLikeRepository implements LikeRepositoryInterface
{
    public function upsert(LikeRequestDTO $data): Like
    {
        return Like::query()->updateOrCreate(
            ['id' => $data->id],
            [
                'user_id' => $data->userId,
                'likeable_id' => $data->likeableId,
                'likeable_type' => $data->likeableType,
            ],
        );
    }

    public function delete(int $id): bool
    {
        return Like::whereKey($id)->delete() > 0;
    }
}
