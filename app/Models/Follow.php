<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasUuids;

    protected $fillable = [
        'follower_id',
        'following_id',
    ];
}
