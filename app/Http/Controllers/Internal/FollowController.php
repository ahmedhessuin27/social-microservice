<?php

declare(strict_types=1);

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Internal\FollowRequest;
use App\Repositories\Contracts\FollowRepositoryInterface;
use Illuminate\Http\JsonResponse;

/**
 * @group Follows
 *
 * Sync a consuming backend's follow relationships into the feed microservice's own copy.
 * Unlike posts/comments/likes, follows have no path {id} — they're keyed by
 * the (follower_id, following_id) pair, sent in the request body for both endpoints.
 */
final class FollowController extends Controller
{
    public function __construct(
        private readonly FollowRepositoryInterface $follows,
    ) {}

    /**
     * Sync a follow relationship
     *
     * Idempotent: calling this twice with the same pair has no additional effect.
     *
     * @header X-Internal-Timestamp required Unix timestamp used in the HMAC signature. Example: 1735689600
     * @header X-Internal-Signature required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9
     *
     * @response 200 {"status": 200, "message": "Follow relationship synced successfully."}
     * @response 422 scenario="Validation error" {"status": 422, "message": "The given data was invalid.", "error": {"following_id": ["The following id and follower id must be different."]}}
     */
    public function update(FollowRequest $request): JsonResponse
    {
        $this->follows->upsert($request->toDto());

        return response()->api(__('social.follow_synced'), 200);
    }

    /**
     * Delete a follow relationship
     *
     * Idempotent: returns 200 whether or not the relationship existed locally.
     *
     * @header X-Internal-Timestamp required Unix timestamp used in the HMAC signature. Example: 1735689600
     * @header X-Internal-Signature required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9
     *
     * @response 200 {"status": 200, "message": "Follow relationship deleted successfully."}
     */
    public function destroy(FollowRequest $request): JsonResponse
    {
        $dto = $request->toDto();

        $this->follows->delete($dto->followerId, $dto->followingId);

        return response()->api(__('social.follow_deleted'), 200);
    }
}
