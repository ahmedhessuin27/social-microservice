<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * The post_hashtags pivot table has its own UUID primary key (per this
 * service's "every table uses a UUID PK" convention), which Eloquent's
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
