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
 * Read the merged feed (own posts + followed users' posts) for a given user.
 */
final class FeedController extends Controller
{
    public function __construct(
        private readonly FeedServiceInterface $feed,
    ) {}

    /**
     * Get a user's feed
     *
     * Returns the user's own posts and their followed users' posts, newest
     * first, cursor-paginated. Draft/hidden posts are excluded.
     *
     * @urlParam userId string required The id of the user whose feed to build, as used by the calling backend. Example: 0199c2b1-1a1a-7000-8b3a-1a2b3c4d5e6f
     *
     * @queryParam per_page integer Number of posts per page (1-50). Defaults to 15. Example: 15
     *
     * @header X-Internal-Timestamp required Unix timestamp used in the HMAC signature. Example: 1735689600
     * @header X-Internal-Signature required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9
     *
     * @response 200 {
     *   "status": 200,
     *   "message": "Feed retrieved successfully.",
     *   "data": [
     *     {
     *       "id": "0199c2b1-2f2b-7000-8b3a-1a2b3c4d5e6f",
     *       "author_id": "0199c2b1-1a1a-7000-8b3a-1a2b3c4d5e6f",
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
     *     "next": "http://localhost/internal/feed/0199c2b1-1a1a-7000-8b3a-1a2b3c4d5e6f?cursor=eyJwb3N0ZWRfYXQiOiIyMDI2LTA4LTA2IDEyOjAwOjAwIn0"
     *   }
     * }
     */
    public function index(FeedRequest $request, string $userId): JsonResponse
    {
        $posts = $this->feed->forUser($userId, $request->perPage());

        return response()->api(
            __('feed.retrieved'),
            200,
            BaseResource::make(PostResource::collection($posts)),
        );
    }
}
