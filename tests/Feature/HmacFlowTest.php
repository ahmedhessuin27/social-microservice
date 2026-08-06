<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Services\Contracts\HmacSignerInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
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
        $me = (string) Str::uuid();
        $friend = (string) Str::uuid();
        $postId = (string) Str::uuid();
        $friendPostId = (string) Str::uuid();

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
}
