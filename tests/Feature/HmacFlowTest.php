<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Services\Contracts\HmacSignerInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class HmacFlowTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, string>
     */
    private function signedHeaders(string $method, string $path, string $body): array
    {
        $signer = app(HmacSignerInterface::class);
        $ts = (string) time();

        return [
            'X-Internal-Timestamp' => $ts,
            'X-Internal-Signature' => $signer->sign($method, $path, $ts, $body),
        ];
    }

    public function test_full_sync_and_feed_flow_through_real_routes_with_hmac(): void
    {
        $me = 1;
        $friend = 2;
        $postId = 101;
        $friendPostId = 102;

        $this->putJson("/internal/posts/{$postId}", [])->assertStatus(401);

        $followBody = json_encode(['follower_id' => $me, 'following_id' => $friend]);
        $this->withHeaders($this->signedHeaders('PUT', 'internal/follows', $followBody))
            ->putJson('/internal/follows', json_decode($followBody, true))
            ->assertStatus(200);

        $postPayload = [
            'author_id' => $me, 'content' => 'hello', 'type' => 'text', 'status' => 'published',
            'likes_count' => 0, 'comments_count' => 0, 'reposts_count' => 0, 'reposted_from_id' => null,
            'posted_at' => now()->toISOString(),
        ];
        $postBody = json_encode($postPayload);
        $this->withHeaders($this->signedHeaders('PUT', "internal/posts/{$postId}", $postBody))
            ->putJson("/internal/posts/{$postId}", $postPayload)
            ->assertStatus(200);

        $friendPostPayload = [
            'author_id' => $friend, 'content' => 'friend post', 'type' => 'text', 'status' => 'published',
            'likes_count' => 0, 'comments_count' => 0, 'reposts_count' => 0, 'reposted_from_id' => null,
            'posted_at' => now()->toISOString(),
        ];
        $friendPostBody = json_encode($friendPostPayload);
        $this->withHeaders($this->signedHeaders('PUT', "internal/posts/{$friendPostId}", $friendPostBody))
            ->putJson("/internal/posts/{$friendPostId}", $friendPostPayload)
            ->assertStatus(200);

        // Laravel's getJson() test helper always json_encode()s its $data
        // param (default []), so it sends a literal "[]" body even for a
        // "bodyless" GET — sign against that, matching what's actually sent.
        $feedResponse = $this->withHeaders($this->signedHeaders('GET', "internal/feed/{$me}", '[]'))
            ->getJson("/internal/feed/{$me}")
            ->assertStatus(200);

        $feedResponse->assertJsonPath('status', 200);
        $this->assertCount(2, $feedResponse->json('data'));

        $this->deleteJson("/internal/posts/{$postId}")->assertStatus(401);

        $deleteHeaders = $this->signedHeaders('DELETE', "internal/posts/{$postId}", '[]');
        $this->withHeaders($deleteHeaders)->deleteJson("/internal/posts/{$postId}")->assertStatus(200);
        $this->withHeaders($deleteHeaders)->deleteJson("/internal/posts/{$postId}")->assertStatus(401);
    }

    public function test_global_feed_returns_every_authors_published_posts_without_any_follows(): void
    {
        $posts = [
            201 => ['author_id' => 1, 'status' => 'published', 'posted_at' => now()->subMinutes(2)],
            202 => ['author_id' => 2, 'status' => 'published', 'posted_at' => now()->subMinute()],
            203 => ['author_id' => 3, 'status' => 'draft', 'posted_at' => now()],
        ];

        foreach ($posts as $id => $post) {
            $payload = [
                'author_id' => $post['author_id'], 'content' => "post {$id}", 'type' => 'text', 'status' => $post['status'],
                'likes_count' => 0, 'comments_count' => 0, 'reposts_count' => 0, 'reposted_from_id' => null,
                'posted_at' => $post['posted_at']->toISOString(),
            ];
            $body = json_encode($payload);
            $this->withHeaders($this->signedHeaders('PUT', "internal/posts/{$id}", $body))
                ->putJson("/internal/posts/{$id}", $payload)
                ->assertStatus(200);
        }

        $response = $this->withHeaders($this->signedHeaders('GET', 'internal/feed', '[]'))
            ->getJson('/internal/feed')
            ->assertStatus(200);

        $this->assertSame([202, 201], array_column($response->json('data'), 'id'));
    }

    public function test_resyncing_a_deleted_post_and_comment_restores_them(): void
    {
        $postPayload = [
            'author_id' => 1, 'content' => 'back again', 'type' => 'text', 'status' => 'published',
            'likes_count' => 0, 'comments_count' => 1, 'reposts_count' => 0, 'reposted_from_id' => null,
            'posted_at' => now()->toISOString(),
        ];
        $commentPayload = [
            'post_id' => 301, 'author_id' => 2, 'text' => 'hi', 'parent_id' => null,
            'likes_count' => 0, 'replies_count' => 0,
        ];

        $sync = function (string $method, string $path, ?array $payload = null) {
            $body = $payload === null ? '[]' : json_encode($payload);

            return $this->withHeaders($this->signedHeaders($method, $path, $body))
                ->json($method, "/{$path}", $payload ?? []);
        };

        $sync('PUT', 'internal/posts/301', $postPayload)->assertStatus(200);
        $sync('PUT', 'internal/comments/401', $commentPayload)->assertStatus(200);
        $sync('DELETE', 'internal/comments/401')->assertStatus(200);
        $sync('DELETE', 'internal/posts/301')->assertStatus(200);

        // Each re-sync is a different body from the original PUT, so it isn't a replay.
        $sync('PUT', 'internal/posts/301', [...$postPayload, 'likes_count' => 1])->assertStatus(200);
        $sync('PUT', 'internal/comments/401', [...$commentPayload, 'likes_count' => 1])->assertStatus(200);

        $this->assertSame([301], array_column($sync('GET', 'internal/feed')->json('data'), 'id'));
        $this->assertDatabaseHas('comments', ['id' => 401, 'deleted_at' => null, 'likes_count' => 1]);
    }

    public function test_identical_reads_in_the_same_second_are_not_rejected_as_replays(): void
    {
        $headers = $this->signedHeaders('GET', 'internal/feed', '[]');

        $this->withHeaders($headers)->getJson('/internal/feed')->assertStatus(200);
        $this->withHeaders($headers)->getJson('/internal/feed')->assertStatus(200);
    }
}
