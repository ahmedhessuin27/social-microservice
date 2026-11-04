<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Services\Contracts\HmacSignerInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ExceptionRenderingTest extends TestCase
{
    use RefreshDatabase;

    private function signedHeaders(string $method, string $path, string $body): array
    {
        $signer = app(HmacSignerInterface::class);
        $ts = (string) time();

        return [
            'X-Internal-Timestamp' => $ts,
            'X-Internal-Signature' => $signer->sign($method, $path, $ts, $body),
        ];
    }

    public function test_unknown_route_returns_api_shaped_404(): void
    {
        $response = $this->getJson('/internal/does-not-exist');

        $response->assertStatus(404);
        $response->assertJson([
            'status' => 404,
            'message' => 'The requested resource was not found.',
        ]);
    }

    public function test_invalid_payload_returns_api_shaped_422_with_field_errors(): void
    {
        $id = 1;
        $body = json_encode(['type' => 'bogus']);

        $response = $this->withHeaders($this->signedHeaders('PUT', "internal/posts/{$id}", $body))
            ->putJson("/internal/posts/{$id}", json_decode($body, true));

        $response->assertStatus(422);
        $response->assertJsonPath('status', 422);
        $response->assertJsonPath('message', 'The given data was invalid.');
        $response->assertJsonStructure(['error' => ['author_id', 'type']]);
    }

    public function test_arabic_locale_translates_error_messages(): void
    {
        $response = $this->withHeaders(['Accept-Language' => 'ar'])->getJson('/internal/does-not-exist');

        $response->assertStatus(404);
        $response->assertJsonPath('message', 'المورد المطلوب غير موجود.');
    }

    public function test_generic_exception_returns_generic_500_without_leaking_details_when_debug_off(): void
    {
        config(['app.debug' => false]);

        // reposted_from_id passes integer validation but points at a post that
        // doesn't exist, tripping the FK constraint -> QueryException,
        // exercising the generic Throwable -> 500 branch.
        $id = 1;
        $body = json_encode([
            'author_id' => 1, 'content' => 'x', 'type' => 'text', 'status' => 'published',
            'likes_count' => 0, 'comments_count' => 0, 'reposts_count' => 0,
            'reposted_from_id' => 999,
            'posted_at' => now()->toISOString(),
        ]);

        $response = $this->withHeaders($this->signedHeaders('PUT', "internal/posts/{$id}", $body))
            ->putJson("/internal/posts/{$id}", json_decode($body, true));

        $response->assertStatus(500);
        $response->assertJson([
            'status' => 500,
            'message' => 'An unexpected error occurred. Please try again later.',
        ]);
        $this->assertStringNotContainsString('SQLSTATE', $response->getContent());
    }
}
