<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent\Concerns;

use Illuminate\Database\Eloquent\Model;

trait AdjustsCounters
{
    /**
     * Atomically add to (or subtract from) a denormalized counter column.
     * Goes through the base query builder on purpose: an Eloquent
     * increment would also bump updated_at, but a new like or comment
     * isn't an edit of the post/comment it's counted on.
     */
    private function adjustCounter(Model $model, string $column, int $amount): void
    {
        $query = $model->newQueryWithoutScopes()->whereKey($model->getKey())->toBase();

        if ($amount >= 0) {
            $query->increment($column, $amount);

            return;
        }

        // Never drop below zero, even if the counter was already off.
        $query->where($column, '>=', -$amount)->decrement($column, -$amount);
    }
}
