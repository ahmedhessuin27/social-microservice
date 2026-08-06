<?php

declare(strict_types=1);

use App\Http\Controllers\Internal\CommentController;
use App\Http\Controllers\Internal\FeedController;
use App\Http\Controllers\Internal\FollowController;
use App\Http\Controllers\Internal\LikeController;
use App\Http\Controllers\Internal\PostController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Internal API Routes
|--------------------------------------------------------------------------
|
| All routes here are prefixed with /internal and protected by the
| VerifyHmacSignature middleware (see bootstrap/app.php). Sync routes
| are keyed by the calling application's own id for that entity,
| making PUT/DELETE idempotent by design.
|
*/

Route::put('posts/{id}', [PostController::class, 'update'])->whereUuid('id');
Route::delete('posts/{id}', [PostController::class, 'destroy'])->whereUuid('id');

Route::put('comments/{id}', [CommentController::class, 'update'])->whereUuid('id');
Route::delete('comments/{id}', [CommentController::class, 'destroy'])->whereUuid('id');

Route::put('likes/{id}', [LikeController::class, 'update'])->whereUuid('id');
Route::delete('likes/{id}', [LikeController::class, 'destroy'])->whereUuid('id');

Route::put('follows', [FollowController::class, 'update']);
Route::delete('follows', [FollowController::class, 'destroy']);

Route::get('feed/{userId}', [FeedController::class, 'index'])->whereUuid('userId');
