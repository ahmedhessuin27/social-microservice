<?php

declare(strict_types=1);

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Internal\PostRequest;
use App\Repositories\Contracts\PostRepositoryInterface;
use Illuminate\Http\JsonResponse;

/**
 * @group Posts
 *
 * Sync a consuming backend's posts into the feed microservice's own copy.
 */
final class PostController extends Controller
{
    public function __construct(
        private readonly PostRepositoryInterface $posts,
    ) {}

    /**
     * Sync a post
     *
     * Idempotent upsert: creates the post if it doesn't exist locally yet, or
     * updates it if it does. The {id} must be the same id the calling backend
     * uses for this post — it becomes this service's primary key too.
     *
     * @urlParam id string required The post's id, as used by the calling backend. Example: 0199c2b1-2f2b-7000-8b3a-1a2b3c4d5e6f
     *
     * @header X-Internal-Timestamp required Unix timestamp used in the HMAC signature. Example: 1735689600
     * @header X-Internal-Signature required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9
     *
     * @response 200 {"status": 200, "message": "Post synced successfully."}
     * @response 422 scenario="Validation error" {"status": 422, "message": "The given data was invalid.", "error": {"author_id": ["The author id field is required."]}}
     */
    public function update(PostRequest $request, string $id): JsonResponse
    {
        $this->posts->upsert($request->toDto($id));

        return response()->api(__('sync.post_synced'), 200);
    }

    /**
     * Delete a post
     *
     * Idempotent: returns 200 whether or not the post existed locally.
     *
     * @urlParam id string required The post's id, as used by the calling backend. Example: 0199c2b1-2f2b-7000-8b3a-1a2b3c4d5e6f
     *
     * @header X-Internal-Timestamp required Unix timestamp used in the HMAC signature. Example: 1735689600
     * @header X-Internal-Signature required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9
     *
     * @response 200 {"status": 200, "message": "Post deleted successfully."}
     */
    public function destroy(string $id): JsonResponse
    {
        $this->posts->delete($id);

        return response()->api(__('sync.post_deleted'), 200);
    }
}
