<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * The post_hashtags pivot table has its own locally generated UUID primary
 * key (like every table whose rows this service creates itself), which Eloquent's
 * default anonymous pivot won't generate on its own — hence a real
 * pivot model here, wired via ->using() on both sides of the relation.
 */
class PostHashtag extends Pivot
{
    use HasUuids;

    protected $fillable = [
        'post_id',
        'hashtag_id',
    ];
}
