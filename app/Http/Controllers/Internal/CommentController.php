<?php

declare(strict_types=1);

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Internal\CommentRequest;
use App\Repositories\Contracts\CommentRepositoryInterface;
use Illuminate\Http\JsonResponse;

/**
 * @group Comments
 *
 * Sync a consuming backend's post comments into the feed microservice's own copy.
 */
final class CommentController extends Controller
{
    public function __construct(
        private readonly CommentRepositoryInterface $comments,
    ) {}

    /**
     * Sync a comment
     *
     * Idempotent upsert. The parent post must already be synced — if it
     * isn't yet, this fails on the foreign key and should be retried.
     *
     * @urlParam id string required The comment's id, as used by the calling backend. Example: 0199c2b1-3a3a-7000-8b3a-1a2b3c4d5e6f
     *
     * @header X-Internal-Timestamp required Unix timestamp used in the HMAC signature. Example: 1735689600
     * @header X-Internal-Signature required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9
     *
     * @response 200 {"status": 200, "message": "Comment synced successfully."}
     * @response 422 scenario="Validation error" {"status": 422, "message": "The given data was invalid.", "error": {"post_id": ["The post id field is required."]}}
     */
    public function update(CommentRequest $request, string $id): JsonResponse
    {
        $this->comments->upsert($request->toDto($id));

        return response()->api(__('sync.comment_synced'), 200);
    }

    /**
     * Delete a comment
     *
     * Idempotent: returns 200 whether or not the comment existed locally.
     *
     * @urlParam id string required The comment's id, as used by the calling backend. Example: 0199c2b1-3a3a-7000-8b3a-1a2b3c4d5e6f
     *
     * @header X-Internal-Timestamp required Unix timestamp used in the HMAC signature. Example: 1735689600
     * @header X-Internal-Signature required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9
     *
     * @response 200 {"status": 200, "message": "Comment deleted successfully."}
     */
    public function destroy(string $id): JsonResponse
    {
        $this->comments->delete($id);

        return response()->api(__('sync.comment_deleted'), 200);
    }
}
