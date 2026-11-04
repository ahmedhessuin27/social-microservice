<?php

// Copy to app/Services/FeedClient.php in the project that uses the feed
// microservice. Needs FEED_SERVICE_URL / FEED_SERVICE_SECRET in .env and
// a 'feed' entry in config/services.php (see DEPLOYMENT.md).
// Written for PHP 7.3+ and Laravel 8+, so it runs on older consuming apps.

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class FeedClient
{
    // ---- Posts --------------------------------------------------------

    /**
     * @param  array{content?: ?string, type: string, status?: string, media?: array, hashtags?: array, reposted_from_id?: ?int}  $data
     */
    public function createPost(int $authorId, array $data): Response
    {
        // author_id last, so nothing in $data can override who the author is.
        return $this->send('POST', 'posts', array_merge($data, ['author_id' => $authorId]));
    }

    public function getPost(int $postId, ?int $viewerId = null): Response
    {
        return $this->send('GET', "posts/{$postId}", null, ['viewer_id' => $viewerId]);
    }

    /**
     * Only the author can edit. Fields left out of $data stay unchanged.
     */
    public function updatePost(int $postId, int $userId, array $data): Response
    {
        return $this->send('PUT', "posts/{$postId}", array_merge($data, ['user_id' => $userId]));
    }

    public function deletePost(int $postId, int $userId): Response
    {
        return $this->send('DELETE', "posts/{$postId}", ['user_id' => $userId]);
    }

    /**
     * Every user's published posts, newest first. Pass the previous
     * response's next_cursor to get the next page.
     */
    public function feed(?int $viewerId = null, ?string $cursor = null, int $perPage = 15): Response
    {
        return $this->send('GET', 'feed', null, ['viewer_id' => $viewerId, 'cursor' => $cursor, 'per_page' => $perPage]);
    }

    public function userPosts(int $userId, ?int $viewerId = null, ?string $cursor = null, int $perPage = 15): Response
    {
        return $this->send('GET', "users/{$userId}/posts", null, ['viewer_id' => $viewerId, 'cursor' => $cursor, 'per_page' => $perPage]);
    }

    // ---- Comments -----------------------------------------------------

    /**
     * Top-level comments, oldest first; pass $parentId for a comment's replies.
     */
    public function comments(int $postId, ?int $viewerId = null, ?int $parentId = null, ?string $cursor = null, int $perPage = 15): Response
    {
        return $this->send('GET', "posts/{$postId}/comments", null, [
            'viewer_id' => $viewerId, 'parent_id' => $parentId, 'cursor' => $cursor, 'per_page' => $perPage,
        ]);
    }

    public function addComment(int $postId, int $authorId, string $text, ?int $parentId = null): Response
    {
        return $this->send('POST', "posts/{$postId}/comments", ['author_id' => $authorId, 'text' => $text, 'parent_id' => $parentId]);
    }

    public function updateComment(int $commentId, int $userId, string $text): Response
    {
        return $this->send('PUT', "comments/{$commentId}", ['user_id' => $userId, 'text' => $text]);
    }

    public function deleteComment(int $commentId, int $userId): Response
    {
        return $this->send('DELETE', "comments/{$commentId}", ['user_id' => $userId]);
    }

    // ---- Likes --------------------------------------------------------

    public function likePost(int $postId, int $userId): Response
    {
        return $this->send('PUT', "posts/{$postId}/likes", ['user_id' => $userId]);
    }

    public function unlikePost(int $postId, int $userId): Response
    {
        return $this->send('DELETE', "posts/{$postId}/likes", ['user_id' => $userId]);
    }

    public function likeComment(int $commentId, int $userId): Response
    {
        return $this->send('PUT', "comments/{$commentId}/likes", ['user_id' => $userId]);
    }

    public function unlikeComment(int $commentId, int $userId): Response
    {
        return $this->send('DELETE', "comments/{$commentId}/likes", ['user_id' => $userId]);
    }

    /**
     * Returns the response as-is (no exception on 4xx), so callers can pass
     * the microservice's status and message straight through to the app.
     */
    private function send(string $method, string $path, ?array $payload = null, array $query = []): Response
    {
        if ($payload !== null) {
            // The microservice rejects a byte-identical write signed in the
            // same second as a replay; a unique field keeps legitimate
            // repeats (e.g. a double-tapped like) distinct. It's ignored.
            $payload['_nonce'] = (string) Str::uuid();
        }

        // Signed string: METHOD\nPATH\nTIMESTAMP\nBODY. PATH has no leading
        // slash and no query string.
        $path = 'internal/'.$path;
        $body = $payload === null ? '' : json_encode($payload);
        $timestamp = (string) time();
        $signature = hash_hmac(
            'sha256',
            implode("\n", [$method, $path, $timestamp, $body]),
            config('services.feed.secret'),
        );

        $request = Http::baseUrl(config('services.feed.url'))
            ->acceptJson()
            ->timeout(10)
            ->withHeaders([
                'X-Internal-Timestamp' => $timestamp,
                'X-Internal-Signature' => $signature,
            ]);

        if ($body !== '') {
            // Send the exact bytes that were signed.
            $request = $request->withBody($body, 'application/json');
        }

        $query = array_filter($query, function ($value) {
            return $value !== null;
        });

        return $request->send($method, $path, ['query' => $query]);
    }
}
