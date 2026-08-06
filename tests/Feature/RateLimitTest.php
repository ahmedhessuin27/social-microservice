<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Services\Contracts\HmacSignerInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class RateLimitTest extends TestCase
{
    use RefreshDatabase;

    private function signedHeaders(string $method, string $path, string $body, int $ts): array
    {
        $signer = app(HmacSignerInterface::class);

        return [
            'X-Internal-Timestamp' => (string) $ts,
            'X-Internal-Signature' => $signer->sign($method, $path, (string) $ts, $body),
        ];
    }

    public function test_exceeding_the_rate_limit_returns_api_shaped_429(): void
    {
        config(['internal-api.rate_limit_per_minute' => 2]);

        $now = time();

        for ($i = 0; $i < 2; $i++) {
            $this->withHeaders($this->signedHeaders('GET', 'internal/feed/00000000-0000-0000-0000-000000000000', '[]', $now + $i))
                ->getJson('/internal/feed/00000000-0000-0000-0000-000000000000')
                ->assertStatus(200);
        }

        $response = $this->withHeaders($this->signedHeaders('GET', 'internal/feed/00000000-0000-0000-0000-000000000000', '[]', $now + 2))
            ->getJson('/internal/feed/00000000-0000-0000-0000-000000000000');

        $response->assertStatus(429);
        $response->assertJson([
            'status' => 429,
            'message' => 'Too many requests. Please slow down.',
        ]);
    }
}
