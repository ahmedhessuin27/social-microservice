<?php

declare(strict_types=1);

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Internal\ActorRequest;
use App\Models\Comment;
use App\Models\Post;
use App\Repositories\Contracts\LikeRepositoryInterface;
use Illuminate\Http\JsonResponse;

/**
 * @group Likes
 *
 * Like and unlike posts and comments. Both are idempotent: liking twice
 * counts once, and unliking something not liked changes nothing.
 */
final class LikeController extends Controller
{
    public function __construct(
        private readonly LikeRepositoryInterface $likes,
    ) {}

    /**
     * Like a post
     *
     * @urlParam post integer required The post's id. Example: 1001
     *
     * @header X-Internal-Timestamp required Unix timestamp used in the HMAC signature. Example: 1735689600
     * @header X-Internal-Signature required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9
     *
     * @response 200 {"status": 200, "message": "Liked successfully.", "data": {"liked": true, "likes_count": 4}}
     */
    public function likePost(ActorRequest $request, Post $post): JsonResponse
    {
        abort_unless($post->isVisibleTo($request->userId()), 404);

        return $this->respond(true, $this->likes->like($post, $request->userId()));
    }

    /**
     * Unlike a post
     *
     * @urlParam post integer required The post's id. Example: 1001
     *
     * @header X-Internal-Timestamp required Unix timestamp used in the HMAC signature. Example: 1735689600
     * @header X-Internal-Signature required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9
     *
     * @response 200 {"status": 200, "message": "Like removed successfully.", "data": {"liked": false, "likes_count": 3}}
     */
    public function unlikePost(ActorRequest $request, Post $post): JsonResponse
    {
        return $this->respond(false, $this->likes->unlike($post, $request->userId()));
    }

    /**
     * Like a comment
     *
     * @urlParam comment integer required The comment's id. Example: 2001
     *
     * @header X-Internal-Timestamp required Unix timestamp used in the HMAC signature. Example: 1735689600
     * @header X-Internal-Signature required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9
     *
     * @response 200 {"status": 200, "message": "Liked successfully.", "data": {"liked": true, "likes_count": 1}}
     */
    public function likeComment(ActorRequest $request, Comment $comment): JsonResponse
    {
        abort_unless($comment->post?->isVisibleTo($request->userId()), 404);

        return $this->respond(true, $this->likes->like($comment, $request->userId()));
    }

    /**
     * Unlike a comment
     *
     * @urlParam comment integer required The comment's id. Example: 2001
     *
     * @header X-Internal-Timestamp required Unix timestamp used in the HMAC signature. Example: 1735689600
     * @header X-Internal-Signature required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9
     *
     * @response 200 {"status": 200, "message": "Like removed successfully.", "data": {"liked": false, "likes_count": 0}}
     */
    public function unlikeComment(ActorRequest $request, Comment $comment): JsonResponse
    {
        return $this->respond(false, $this->likes->unlike($comment, $request->userId()));
    }

    private function respond(bool $liked, int $likesCount): JsonResponse
    {
        return response()->api(
            __($liked ? 'social.liked' : 'social.unliked'),
            200,
            ['liked' => $liked, 'likes_count' => $likesCount],
        );
    }
}
