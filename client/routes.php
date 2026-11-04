<?php

// Add to routes/api.php in the project that uses the feed microservice.
// Change 'auth:sanctum' to whatever login guard that project's API uses.

use App\Http\Controllers\SocialController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('social')->controller(SocialController::class)->group(function (): void {
    Route::get('feed', 'feed');
    Route::get('users/{userId}/posts', 'userPosts')->whereNumber('userId');

    Route::post('posts', 'createPost');
    Route::get('posts/{postId}', 'showPost')->whereNumber('postId');
    Route::put('posts/{postId}', 'updatePost')->whereNumber('postId');
    Route::delete('posts/{postId}', 'deletePost')->whereNumber('postId');

    Route::get('posts/{postId}/comments', 'comments')->whereNumber('postId');
    Route::post('posts/{postId}/comments', 'addComment')->whereNumber('postId');
    Route::put('comments/{commentId}', 'updateComment')->whereNumber('commentId');
    Route::delete('comments/{commentId}', 'deleteComment')->whereNumber('commentId');

    Route::post('posts/{postId}/like', 'likePost')->whereNumber('postId');
    Route::delete('posts/{postId}/like', 'unlikePost')->whereNumber('postId');
    Route::post('comments/{commentId}/like', 'likeComment')->whereNumber('commentId');
    Route::delete('comments/{commentId}/like', 'unlikeComment')->whereNumber('commentId');
});
