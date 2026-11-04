<?php

// Add to routes/api.php in the project that uses the feed microservice.
// Change 'auth:sanctum' to whatever login guard that project's API uses.
// Written for Laravel 8+ (no Route::controller() groups, which need 8.80+).

use App\Http\Controllers\SocialController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('social')->group(function () {
    Route::get('feed', [SocialController::class, 'feed']);
    Route::get('users/{userId}/posts', [SocialController::class, 'userPosts'])->where('userId', '[0-9]+');

    Route::post('posts', [SocialController::class, 'createPost']);
    Route::get('posts/{postId}', [SocialController::class, 'showPost'])->where('postId', '[0-9]+');
    Route::put('posts/{postId}', [SocialController::class, 'updatePost'])->where('postId', '[0-9]+');
    Route::delete('posts/{postId}', [SocialController::class, 'deletePost'])->where('postId', '[0-9]+');

    Route::get('posts/{postId}/comments', [SocialController::class, 'comments'])->where('postId', '[0-9]+');
    Route::post('posts/{postId}/comments', [SocialController::class, 'addComment'])->where('postId', '[0-9]+');
    Route::put('comments/{commentId}', [SocialController::class, 'updateComment'])->where('commentId', '[0-9]+');
    Route::delete('comments/{commentId}', [SocialController::class, 'deleteComment'])->where('commentId', '[0-9]+');

    Route::post('posts/{postId}/like', [SocialController::class, 'likePost'])->where('postId', '[0-9]+');
    Route::delete('posts/{postId}/like', [SocialController::class, 'unlikePost'])->where('postId', '[0-9]+');
    Route::post('comments/{commentId}/like', [SocialController::class, 'likeComment'])->where('commentId', '[0-9]+');
    Route::delete('comments/{commentId}/like', [SocialController::class, 'unlikeComment'])->where('commentId', '[0-9]+');
});
