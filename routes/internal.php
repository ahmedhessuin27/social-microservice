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
| VerifyHmacSignature middleware (see bootstrap/app.php). This service is
| the source of truth for posts, comments and likes; the calling backend
| passes its logged-in user's id (author_id / user_id / viewer_id) and this
| service enforces ownership.
|
*/

Route::post('posts', [PostController::class, 'store']);
Route::get('posts/{post}', [PostController::class, 'show'])->whereNumber('post');
Route::put('posts/{post}', [PostController::class, 'update'])->whereNumber('post');
Route::delete('posts/{post}', [PostController::class, 'destroy'])->whereNumber('post');

Route::get('posts/{post}/comments', [CommentController::class, 'index'])->whereNumber('post');
Route::post('posts/{post}/comments', [CommentController::class, 'store'])->whereNumber('post');
Route::put('comments/{comment}', [CommentController::class, 'update'])->whereNumber('comment');
Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->whereNumber('comment');

Route::put('posts/{post}/likes', [LikeController::class, 'likePost'])->whereNumber('post');
Route::delete('posts/{post}/likes', [LikeController::class, 'unlikePost'])->whereNumber('post');
Route::put('comments/{comment}/likes', [LikeController::class, 'likeComment'])->whereNumber('comment');
Route::delete('comments/{comment}/likes', [LikeController::class, 'unlikeComment'])->whereNumber('comment');

Route::put('follows', [FollowController::class, 'update']);
Route::delete('follows', [FollowController::class, 'destroy']);

Route::get('feed', [FeedController::class, 'latest']);
Route::get('feed/{userId}', [FeedController::class, 'index'])->whereNumber('userId');
Route::get('users/{userId}/posts', [FeedController::class, 'byAuthor'])->whereNumber('userId');
