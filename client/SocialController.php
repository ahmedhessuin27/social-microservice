<?php

// Copy to app/Http/Controllers/SocialController.php in the project that uses
// the feed microservice, and add the routes from client/routes.php.
//
// The app (mobile/web) talks to these routes with its normal login; this
// controller adds the logged-in user's id and forwards to the microservice.
// Never take a user id from the app's request body — always $request->user().
// Written for PHP 7.3+ and Laravel 8+, so it runs on older consuming apps.

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\FeedClient;
use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SocialController extends Controller
{
    /** @var FeedClient */
    private $feed;

    public function __construct(FeedClient $feed)
    {
        $this->feed = $feed;
    }

    // ---- Posts --------------------------------------------------------

    public function feed(Request $request): JsonResponse
    {
        return $this->forward($this->feed->feed($request->user()->id, $request->query('cursor')));
    }

    public function userPosts(Request $request, int $userId): JsonResponse
    {
        return $this->forward($this->feed->userPosts($userId, $request->user()->id, $request->query('cursor')));
    }

    public function showPost(Request $request, int $postId): JsonResponse
    {
        return $this->forward($this->feed->getPost($postId, $request->user()->id));
    }

    public function createPost(Request $request): JsonResponse
    {
        return $this->forward($this->feed->createPost(
            $request->user()->id,
            $request->only(['content', 'type', 'status', 'media', 'hashtags', 'reposted_from_id']),
        ));
    }

    public function updatePost(Request $request, int $postId): JsonResponse
    {
        return $this->forward($this->feed->updatePost(
            $postId,
            $request->user()->id,
            $request->only(['content', 'type', 'status', 'media', 'hashtags']),
        ));
    }

    public function deletePost(Request $request, int $postId): JsonResponse
    {
        return $this->forward($this->feed->deletePost($postId, $request->user()->id));
    }

    // ---- Comments -----------------------------------------------------

    public function comments(Request $request, int $postId): JsonResponse
    {
        $parentId = $request->filled('parent_id') ? (int) $request->input('parent_id') : null;

        return $this->forward($this->feed->comments($postId, $request->user()->id, $parentId, $request->query('cursor')));
    }

    public function addComment(Request $request, int $postId): JsonResponse
    {
        $parentId = $request->filled('parent_id') ? (int) $request->input('parent_id') : null;

        return $this->forward($this->feed->addComment($postId, $request->user()->id, (string) $request->input('text'), $parentId));
    }

    public function updateComment(Request $request, int $commentId): JsonResponse
    {
        return $this->forward($this->feed->updateComment($commentId, $request->user()->id, (string) $request->input('text')));
    }

    public function deleteComment(Request $request, int $commentId): JsonResponse
    {
        return $this->forward($this->feed->deleteComment($commentId, $request->user()->id));
    }

    // ---- Likes --------------------------------------------------------

    public function likePost(Request $request, int $postId): JsonResponse
    {
        return $this->forward($this->feed->likePost($postId, $request->user()->id));
    }

    public function unlikePost(Request $request, int $postId): JsonResponse
    {
        return $this->forward($this->feed->unlikePost($postId, $request->user()->id));
    }

    public function likeComment(Request $request, int $commentId): JsonResponse
    {
        return $this->forward($this->feed->likeComment($commentId, $request->user()->id));
    }

    public function unlikeComment(Request $request, int $commentId): JsonResponse
    {
        return $this->forward($this->feed->unlikeComment($commentId, $request->user()->id));
    }

    /**
     * Pass the microservice's response through with the same status code,
     * adding an "author" object to each post/comment from this app's users.
     */
    private function forward(Response $response): JsonResponse
    {
        $body = $response->json() ?? [];

        if (isset($body['data']) && is_array($body['data'])) {
            $body['data'] = $this->withAuthors($body['data']);
        }

        return response()->json($body, $response->status());
    }

    private function withAuthors(array $data): array
    {
        $isList = $data === [] || array_keys($data) === range(0, count($data) - 1);
        $items = $isList ? $data : [$data];

        $authorIds = array_values(array_unique(array_filter(array_column($items, 'author_id'))));

        if ($authorIds === []) {
            return $data;
        }

        // Change the columns to whatever your users table has (e.g. 'avatar').
        $authors = User::query()->whereIn('id', $authorIds)->get(['id', 'name'])->keyBy('id');

        $items = array_map(function (array $item) use ($authors): array {
            $author = $authors->get($item['author_id'] ?? null);
            $item['author'] = $author ? $author->only(['id', 'name']) : null;

            return $item;
        }, $items);

        return $isList ? $items : $items[0];
    }
}
