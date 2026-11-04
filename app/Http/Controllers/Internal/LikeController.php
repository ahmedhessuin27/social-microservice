<?php

declare(strict_types=1);

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Internal\LikeRequest;
use App\Repositories\Contracts\LikeRepositoryInterface;
use Illuminate\Http\JsonResponse;

/**
 * @group Likes
 *
 * Sync a consuming backend's likes (on posts or comments) into the feed microservice's own copy.
 */
final class LikeController extends Controller
{
    public function __construct(
        private readonly LikeRepositoryInterface $likes,
    ) {}

    /**
     * Sync a like
     *
     * Idempotent upsert. likeable_type must be "post" or "comment", and the
     * referenced post/comment must already be synced.
     *
     * @urlParam id integer required The like's id, as used by the calling backend. Example: 3001
     *
     * @header X-Internal-Timestamp required Unix timestamp used in the HMAC signature. Example: 1735689600
     * @header X-Internal-Signature required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9
     *
     * @response 200 {"status": 200, "message": "Like synced successfully."}
     * @response 422 scenario="Validation error" {"status": 422, "message": "The given data was invalid.", "error": {"likeable_type": ["The selected likeable type is invalid."]}}
     */
    public function update(LikeRequest $request, int $id): JsonResponse
    {
        $this->likes->upsert($request->toDto($id));

        return response()->api(__('sync.like_synced'), 200);
    }

    /**
     * Delete a like
     *
     * Idempotent: returns 200 whether or not the like existed locally.
     *
     * @urlParam id integer required The like's id, as used by the calling backend. Example: 3001
     *
     * @header X-Internal-Timestamp required Unix timestamp used in the HMAC signature. Example: 1735689600
     * @header X-Internal-Signature required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9
     *
     * @response 200 {"status": 200, "message": "Like deleted successfully."}
     */
    public function destroy(int $id): JsonResponse
    {
        $this->likes->delete($id);

        return response()->api(__('sync.like_deleted'), 200);
    }
}
