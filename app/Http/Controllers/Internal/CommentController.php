<?php

declare(strict_types=1);

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Internal\ActorRequest;
use App\Http\Requests\Internal\CommentIndexRequest;
use App\Http\Requests\Internal\CommentRequest;
use App\Http\Requests\Internal\UpdateCommentRequest;
use App\Http\Resources\BaseResource;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use App\Repositories\Contracts\CommentRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

/**
 * @group Comments
 *
 * Comments on posts, and replies to comments (one level deep).
 */
final class CommentController extends Controller
{
    public function __construct(
        private readonly CommentRepositoryInterface $comments,
    ) {}

    /**
     * List a post's comments
     *
     * Top-level comments, oldest first, cursor-paginated. Pass parent_id to
     * list the replies to one comment instead.
     *
     * @urlParam post integer required The post's id. Example: 1001
     *
     * @header X-Internal-Timestamp required Unix timestamp used in the HMAC signature. Example: 1735689600
     * @header X-Internal-Signature required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9
     */
    public function index(CommentIndexRequest $request, Post $post): JsonResponse
    {
        abort_unless($post->isVisibleTo($request->viewerId()), 404);

        $comments = $this->comments->forPost($post, $request->parentId(), $request->perPage(), $request->viewerId());

        return response()->api(
            __('social.comments_retrieved'),
            200,
            BaseResource::make(CommentResource::collection($comments)),
        );
    }

    /**
     * Add a comment
     *
     * @urlParam post integer required The post's id. Example: 1001
     *
     * @header X-Internal-Timestamp required Unix timestamp used in the HMAC signature. Example: 1735689600
     * @header X-Internal-Signature required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9
     */
    public function store(CommentRequest $request, Post $post): JsonResponse
    {
        $data = $request->toDto();

        abort_unless($post->isVisibleTo($data->authorId), 404);

        $comment = $this->comments->create($post, $data);

        return response()->api(__('social.comment_created'), 201, BaseResource::make(CommentResource::make($comment)));
    }

    /**
     * Edit a comment
     *
     * Only the comment's author can edit it.
     *
     * @urlParam comment integer required The comment's id. Example: 2001
     *
     * @header X-Internal-Timestamp required Unix timestamp used in the HMAC signature. Example: 1735689600
     * @header X-Internal-Signature required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9
     */
    public function update(UpdateCommentRequest $request, Comment $comment): JsonResponse
    {
        abort_if($comment->post === null, 404);
        throw_unless($comment->author_id === $request->userId(), AuthorizationException::class);

        $comment = $this->comments->update($comment, $request->text());

        return response()->api(__('social.comment_updated'), 200, BaseResource::make(CommentResource::make($comment)));
    }

    /**
     * Delete a comment
     *
     * The comment's author or the post's author can delete it. Its replies
     * are deleted with it.
     *
     * @urlParam comment integer required The comment's id. Example: 2001
     *
     * @header X-Internal-Timestamp required Unix timestamp used in the HMAC signature. Example: 1735689600
     * @header X-Internal-Signature required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9
     */
    public function destroy(ActorRequest $request, Comment $comment): JsonResponse
    {
        abort_if($comment->post === null, 404);
        throw_unless(
            in_array($request->userId(), [$comment->author_id, $comment->post->author_id], true),
            AuthorizationException::class,
        );

        $this->comments->delete($comment);

        return response()->api(__('social.comment_deleted'), 200);
    }
}
