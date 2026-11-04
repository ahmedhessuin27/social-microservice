<?php

declare(strict_types=1);

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Internal\ActorRequest;
use App\Http\Requests\Internal\PostRequest;
use App\Http\Requests\Internal\UpdatePostRequest;
use App\Http\Requests\Internal\ViewerRequest;
use App\Http\Resources\BaseResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Repositories\Contracts\PostRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

/**
 * @group Posts
 *
 * Create, read, edit and delete posts. This service is the source of truth
 * for posts: it assigns their ids and keeps their like/comment/repost counts.
 */
final class PostController extends Controller
{
    public function __construct(
        private readonly PostRepositoryInterface $posts,
    ) {}

    /**
     * Create a post
     *
     * @header X-Internal-Timestamp required Unix timestamp used in the HMAC signature. Example: 1735689600
     * @header X-Internal-Signature required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9
     */
    public function store(PostRequest $request): JsonResponse
    {
        $post = $this->posts->create($request->toDto());

        return response()->api(__('social.post_created'), 201, BaseResource::make(PostResource::make($post)));
    }

    /**
     * Get a post
     *
     * Drafts and hidden posts are only returned when viewer_id is their author.
     *
     * @urlParam post integer required The post's id. Example: 1001
     *
     * @header X-Internal-Timestamp required Unix timestamp used in the HMAC signature. Example: 1735689600
     * @header X-Internal-Signature required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9
     */
    public function show(ViewerRequest $request, Post $post): JsonResponse
    {
        abort_unless($post->isVisibleTo($request->viewerId()), 404);

        $post = $this->posts->loadForViewer($post, $request->viewerId());

        return response()->api(__('social.post_retrieved'), 200, BaseResource::make(PostResource::make($post)));
    }

    /**
     * Edit a post
     *
     * Only the post's author can edit it. Fields left out stay unchanged.
     *
     * @urlParam post integer required The post's id. Example: 1001
     *
     * @header X-Internal-Timestamp required Unix timestamp used in the HMAC signature. Example: 1735689600
     * @header X-Internal-Signature required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9
     */
    public function update(UpdatePostRequest $request, Post $post): JsonResponse
    {
        throw_unless($post->author_id === $request->userId(), AuthorizationException::class);

        $post = $this->posts->update($post, $request->toDto());

        return response()->api(__('social.post_updated'), 200, BaseResource::make(PostResource::make($post)));
    }

    /**
     * Delete a post
     *
     * Only the post's author can delete it.
     *
     * @urlParam post integer required The post's id. Example: 1001
     *
     * @header X-Internal-Timestamp required Unix timestamp used in the HMAC signature. Example: 1735689600
     * @header X-Internal-Signature required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9
     */
    public function destroy(ActorRequest $request, Post $post): JsonResponse
    {
        throw_unless($post->author_id === $request->userId(), AuthorizationException::class);

        $this->posts->delete($post);

        return response()->api(__('social.post_deleted'), 200);
    }
}
