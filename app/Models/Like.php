<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Like extends Model
{
    // Primary key mirrors the consuming application's own integer id.
    public $incrementing = false;

    protected $keyType = 'int';

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
