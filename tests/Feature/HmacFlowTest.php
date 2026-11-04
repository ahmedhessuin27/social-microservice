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
    private function signedHeaders(string $method, string $path, string $body, ?int $timestamp = null): array
    {
        $signer = app(HmacSignerInterface::class);
        $ts = (string) ($timestamp ?? time());

        return [
            'X-Internal-Timestamp' => $ts,
            'X-Internal-Signature' => $signer->sign($method, $path, $ts, $body),
        ];
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function createPost(array $payload, int $timestamp): int
    {
        $body = json_encode($payload);

        return $this->withHeaders($this->signedHeaders('POST', 'internal/posts', $body, $timestamp))
            ->postJson('/internal/posts', $payload)
            ->assertStatus(201)
            ->json('data.id');
    }

    public function test_unsigned_and_wrongly_signed_requests_are_rejected(): void
    {
        $this->postJson('/internal/posts', [])->assertStatus(401);

        $this->withHeaders(['X-Internal-Timestamp' => (string) time(), 'X-Internal-Signature' => 'nope'])
            ->getJson('/internal/feed')
            ->assertStatus(401);
    }

    public function test_follow_feed_merges_own_and_followed_posts_only(): void
    {
        $now = time();
        $followBody = json_encode(['follower_id' => 1, 'following_id' => 2]);
        $this->withHeaders($this->signedHeaders('PUT', 'internal/follows', $followBody))
            ->putJson('/internal/follows', json_decode($followBody, true))
            ->assertStatus(200);

        $mine = $this->createPost(['author_id' => 1, 'content' => 'mine', 'type' => 'text'], $now);
        $friends = $this->createPost(['author_id' => 2, 'content' => 'friend', 'type' => 'text'], $now + 1);
        $this->createPost(['author_id' => 3, 'content' => 'stranger', 'type' => 'text'], $now + 2);

        // Laravel's getJson() test helper always json_encode()s its $data
        // param (default []), so it sends a literal "[]" body even for a
        // "bodyless" GET — sign against that, matching what's actually sent.
        $ids = $this->withHeaders($this->signedHeaders('GET', 'internal/feed/1', '[]'))
            ->getJson('/internal/feed/1')
            ->assertStatus(200)
            ->json('data');

        $this->assertEqualsCanonicalizing([$mine, $friends], array_column($ids, 'id'));
    }

    public function test_replayed_writes_are_rejected(): void
    {
        $body = json_encode(['author_id' => 1, 'content' => 'once', 'type' => 'text']);
        $headers = $this->signedHeaders('POST', 'internal/posts', $body);

        $this->withHeaders($headers)->postJson('/internal/posts', json_decode($body, true))->assertStatus(201);
        $this->withHeaders($headers)->postJson('/internal/posts', json_decode($body, true))->assertStatus(401);
    }

    public function test_identical_reads_in_the_same_second_are_not_rejected_as_replays(): void
    {
        $headers = $this->signedHeaders('GET', 'internal/feed', '[]');

        $this->withHeaders($headers)->getJson('/internal/feed')->assertStatus(200);
        $this->withHeaders($headers)->getJson('/internal/feed')->assertStatus(200);
    }
}
