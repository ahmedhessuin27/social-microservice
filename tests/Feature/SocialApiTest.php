<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Post;
use App\Services\Contracts\HmacSignerInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

final class SocialApiTest extends TestCase
{
    use RefreshDatabase;

    private int $clock = 0;

    /**
     * Signed request through the real routes and HMAC middleware. Each call
     * gets its own timestamp, so repeating an identical write (e.g. liking
     * twice) isn't rejected by replay protection.
     *
     * @param  array<string, mixed>  $data
     */
    private function api(string $method, string $uri, array $data = []): TestResponse
    {
        $timestamp = (string) (time() + $this->clock++);
        $path = ltrim((string) parse_url($uri, PHP_URL_PATH), '/');

        return $this->withHeaders([
            'X-Internal-Timestamp' => $timestamp,
            'X-Internal-Signature' => app(HmacSignerInterface::class)->sign($method, $path, $timestamp, json_encode($data)),
        ])->json($method, $uri, $data);
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function createPost(array $overrides = []): int
    {
        return $this->api('POST', '/internal/posts', [
            'author_id' => 1, 'content' => 'hello', 'type' => 'text', ...$overrides,
        ])->assertStatus(201)->json('data.id');
    }

    public function test_creating_a_post_assigns_an_id_and_starts_counters_at_zero(): void
    {
        $response = $this->api('POST', '/internal/posts', [
            'author_id' => 7, 'content' => 'first', 'type' => 'image',
            'media' => [['url' => 'https://example.com/a.jpg']], 'hashtags' => ['news'],
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('status', true)
            ->assertJsonPath('data.author_id', 7)
            ->assertJsonPath('data.status', 'published')
            ->assertJsonPath('data.likes_count', 0)
            ->assertJsonPath('data.comments_count', 0)
            ->assertJsonPath('data.media.0.url', 'https://example.com/a.jpg')
            ->assertJsonPath('data.hashtags', ['news']);
        $this->assertIsInt($response->json('data.id'));
    }

    public function test_a_post_needs_content_or_media(): void
    {
        $this->api('POST', '/internal/posts', ['author_id' => 1, 'type' => 'text'])
            ->assertStatus(422)
            ->assertJsonStructure(['error' => ['content']]);
    }

    public function test_drafts_are_only_visible_to_their_author(): void
    {
        $id = $this->createPost(['status' => 'draft']);

        $this->api('GET', "/internal/posts/{$id}?viewer_id=2")->assertStatus(404);
        $this->api('GET', "/internal/posts/{$id}?viewer_id=1")->assertStatus(200);
        $this->assertSame([], $this->api('GET', '/internal/feed')->json('data'));
    }

    public function test_only_the_author_can_edit_and_omitted_fields_stay_unchanged(): void
    {
        $id = $this->createPost(['hashtags' => ['keep']]);

        $this->api('PUT', "/internal/posts/{$id}", ['user_id' => 2, 'content' => 'hacked'])
            ->assertStatus(403)
            ->assertJsonPath('status', false);

        $this->api('PUT', "/internal/posts/{$id}", ['user_id' => 1, 'content' => 'edited'])
            ->assertStatus(200)
            ->assertJsonPath('data.content', 'edited')
            ->assertJsonPath('data.hashtags', ['keep']);
    }

    public function test_publishing_a_draft_moves_it_to_the_top_of_the_feed(): void
    {
        Carbon::setTestNow(now()->subHour());
        $draft = $this->createPost(['status' => 'draft']);
        Carbon::setTestNow(now()->addMinutes(30));
        $older = $this->createPost();
        Carbon::setTestNow();

        $this->api('PUT', "/internal/posts/{$draft}", ['user_id' => 1, 'status' => 'published'])->assertStatus(200);

        $this->assertSame([$draft, $older], array_column($this->api('GET', '/internal/feed')->json('data'), 'id'));
    }

    public function test_only_the_author_can_delete_and_reposts_are_counted(): void
    {
        $original = $this->createPost();
        $repost = $this->createPost(['author_id' => 2, 'reposted_from_id' => $original]);
        $this->assertSame(1, Post::find($original)->reposts_count);

        $this->api('DELETE', "/internal/posts/{$repost}", ['user_id' => 1])->assertStatus(403);
        $this->api('DELETE', "/internal/posts/{$repost}", ['user_id' => 2])->assertStatus(200);

        $this->api('GET', "/internal/posts/{$repost}")->assertStatus(404);
        $this->assertSame(0, Post::find($original)->reposts_count);
    }

    public function test_comments_replies_and_their_counters(): void
    {
        $post = $this->createPost();

        $comment = $this->api('POST', "/internal/posts/{$post}/comments", ['author_id' => 2, 'text' => 'nice'])
            ->assertStatus(201)->json('data.id');
        $reply = $this->api('POST', "/internal/posts/{$post}/comments", ['author_id' => 3, 'text' => 'agreed', 'parent_id' => $comment])
            ->assertStatus(201)->json('data.id');

        // Replies are one level deep.
        $this->api('POST', "/internal/posts/{$post}/comments", ['author_id' => 4, 'text' => 'x', 'parent_id' => $reply])
            ->assertStatus(422);

        $this->assertSame(2, Post::find($post)->comments_count);
        $this->assertSame([$comment], array_column($this->api('GET', "/internal/posts/{$post}/comments")->json('data'), 'id'));

        $replies = $this->api('GET', "/internal/posts/{$post}/comments?parent_id={$comment}")->json('data');
        $this->assertSame([$reply], array_column($replies, 'id'));
        $this->assertSame(1, $this->api('GET', "/internal/posts/{$post}/comments")->json('data.0.replies_count'));
    }

    public function test_comment_edit_and_delete_permissions(): void
    {
        $post = $this->createPost();
        $comment = $this->api('POST', "/internal/posts/{$post}/comments", ['author_id' => 2, 'text' => 'nice'])->json('data.id');
        $this->api('POST', "/internal/posts/{$post}/comments", ['author_id' => 3, 'text' => 'reply', 'parent_id' => $comment]);

        // Only the comment's author may edit...
        $this->api('PUT', "/internal/comments/{$comment}", ['user_id' => 1, 'text' => 'x'])->assertStatus(403);
        $this->api('PUT', "/internal/comments/{$comment}", ['user_id' => 2, 'text' => 'edited'])
            ->assertStatus(200)->assertJsonPath('data.text', 'edited');

        // ...but the post's author may also delete, and replies go with it.
        $this->api('DELETE', "/internal/comments/{$comment}", ['user_id' => 3])->assertStatus(403);
        $this->api('DELETE', "/internal/comments/{$comment}", ['user_id' => 1])->assertStatus(200);

        $this->assertSame(0, Post::find($post)->comments_count);
        $this->assertSame([], $this->api('GET', "/internal/posts/{$post}/comments")->json('data'));
    }

    public function test_likes_are_idempotent_and_show_up_for_the_viewer(): void
    {
        $post = $this->createPost();
        $updatedAt = Post::find($post)->updated_at;

        $this->api('PUT', "/internal/posts/{$post}/likes", ['user_id' => 5])->assertJsonPath('data.likes_count', 1);
        $this->api('PUT', "/internal/posts/{$post}/likes", ['user_id' => 5])->assertJsonPath('data.likes_count', 1);
        $this->api('PUT', "/internal/posts/{$post}/likes", ['user_id' => 6])->assertJsonPath('data.likes_count', 2);

        $this->assertTrue($this->api('GET', '/internal/feed?viewer_id=5')->json('data.0.liked'));
        $this->assertFalse($this->api('GET', '/internal/feed?viewer_id=9')->json('data.0.liked'));
        $this->assertArrayNotHasKey('liked', $this->api('GET', '/internal/feed')->json('data.0'));

        $this->api('DELETE', "/internal/posts/{$post}/likes", ['user_id' => 5])
            ->assertJsonPath('data.liked', false)
            ->assertJsonPath('data.likes_count', 1);
        $this->api('DELETE', "/internal/posts/{$post}/likes", ['user_id' => 5])->assertJsonPath('data.likes_count', 1);

        // A like isn't an edit of the post.
        $this->assertEquals($updatedAt, Post::find($post)->updated_at);
    }

    public function test_liking_a_comment(): void
    {
        $post = $this->createPost();
        $comment = $this->api('POST', "/internal/posts/{$post}/comments", ['author_id' => 2, 'text' => 'nice'])->json('data.id');

        $this->api('PUT', "/internal/comments/{$comment}/likes", ['user_id' => 1])->assertJsonPath('data.likes_count', 1);

        $this->assertTrue($this->api('GET', "/internal/posts/{$post}/comments?viewer_id=1")->json('data.0.liked'));
    }

    public function test_a_users_posts(): void
    {
        $mine = $this->createPost(['author_id' => 1]);
        $this->createPost(['author_id' => 2]);

        $this->assertSame([$mine], array_column($this->api('GET', '/internal/users/1/posts')->json('data'), 'id'));
    }
}
