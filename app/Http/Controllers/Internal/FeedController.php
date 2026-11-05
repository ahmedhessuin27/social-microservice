<?php

declare(strict_types=1);

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Internal\FeedRequest;
use App\Http\Resources\BaseResource;
use App\Http\Resources\PostResource;
use App\Services\Contracts\FeedServiceInterface;
use Illuminate\Http\JsonResponse;

/**
 * @group Feed
 *
 * Read published posts, newest first: either every author's posts (for
 * consumers with no follow system) or one user's merged feed (own posts +
 * followed users' posts).
 */
final class FeedController extends Controller
{
    public function __construct(
        private readonly FeedServiceInterface $feed,
    ) {}

    /**
     * Get the global feed
     *
     * Returns every author's published posts, newest first, cursor-paginated.
     * Use this when the calling backend has no follow/following system.
     * Draft/hidden posts are excluded.
     *
     * @header X-Internal-Timestamp required Unix timestamp used in the HMAC signature. Example: 1735689600
     * @header X-Internal-Signature required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9
     *
     * @response 200 {
     *   "status": true,
     *   "message": "Feed retrieved successfully.",
     *   "data": [
     *     {
     *       "id": 1001,
     *       "author_id": 42,
     *       "content": "Hello world",
     *       "type": "text",
     *       "status": "published",
     *       "likes_count": 3,
     *       "comments_count": 1,
     *       "reposts_count": 0,
     *       "reposted_from_id": null,
     *       "posted_at": "2026-08-06T12:00:00.000000Z",
     *       "media": [],
     *       "hashtags": ["laravel"]
     *     }
     *   ],
     *   "next_cursor": "eyJwb3N0ZWRfYXQiOiIyMDI2LTA4LTA2IDEyOjAwOjAwIn0",
     *   "prev_cursor": null,
     *   "per_page": 15,
     *   "links": {
     *     "first": null,
     *     "last": null,
     *     "prev": null,
     *     "next": "http://localhost/internal/feed?cursor=eyJwb3N0ZWRfYXQiOiIyMDI2LTA4LTA2IDEyOjAwOjAwIn0"
     *   }
     * }
     */
    public function latest(FeedRequest $request): JsonResponse
    {
        $posts = $this->feed->latest($request->perPage(), $request->viewerId());

        return response()->api(
            __('feed.retrieved'),
            200,
            BaseResource::make(PostResource::collection($posts)),
        );
    }

    /**
     * Get a user's feed
     *
     * Returns the user's own posts and their followed users' posts, newest
     * first, cursor-paginated. Draft/hidden posts are excluded.
     *
     * @urlParam userId integer required The id of the user whose feed to build, as used by the calling backend. Example: 42
     *
     * @header X-Internal-Timestamp required Unix timestamp used in the HMAC signature. Example: 1735689600
     * @header X-Internal-Signature required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9
     *
     * @response 200 {
     *   "status": true,
     *   "message": "Feed retrieved successfully.",
     *   "data": [
     *     {
     *       "id": 1001,
     *       "author_id": 42,
     *       "content": "Hello world",
     *       "type": "text",
     *       "status": "published",
     *       "likes_count": 3,
     *       "comments_count": 1,
     *       "reposts_count": 0,
     *       "reposted_from_id": null,
     *       "posted_at": "2026-08-06T12:00:00.000000Z",
     *       "media": [],
     *       "hashtags": ["laravel"]
     *     }
     *   ],
     *   "next_cursor": "eyJwb3N0ZWRfYXQiOiIyMDI2LTA4LTA2IDEyOjAwOjAwIn0",
     *   "prev_cursor": null,
     *   "per_page": 15,
     *   "links": {
     *     "first": null,
     *     "last": null,
     *     "prev": null,
     *     "next": "http://localhost/internal/feed/42?cursor=eyJwb3N0ZWRfYXQiOiIyMDI2LTA4LTA2IDEyOjAwOjAwIn0"
     *   }
     * }
     */
    public function index(FeedRequest $request, int $userId): JsonResponse
    {
        $posts = $this->feed->forUser($userId, $request->perPage(), $request->viewerId());

        return response()->api(
            __('feed.retrieved'),
            200,
            BaseResource::make(PostResource::collection($posts)),
        );
    }

    /**
     * Get a user's posts
     *
     * One user's published posts, newest first, cursor-paginated — e.g. for
     * a profile page.
     *
     * @urlParam userId integer required The author's id, as used by the calling backend. Example: 42
     *
     * @header X-Internal-Timestamp required Unix timestamp used in the HMAC signature. Example: 1735689600
     * @header X-Internal-Signature required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9
     */
    public function byAuthor(FeedRequest $request, int $userId): JsonResponse
    {
        $posts = $this->feed->byAuthor($userId, $request->perPage(), $request->viewerId());

        return response()->api(
            __('social.posts_retrieved'),
            200,
            BaseResource::make(PostResource::collection($posts)),
        );
    }
}
