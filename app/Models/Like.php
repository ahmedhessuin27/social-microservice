<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Like extends Model
{
    use HasUuids;

    protected $fillable = [
        'id',
        'user_id',
        'likeable_id',
        'likeable_type',
    ];

    public function likeable(): MorphTo
    {
        return $this->morphTo();
    }
}
