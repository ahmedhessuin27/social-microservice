# Deployment

How to deploy this service to the cPanel/WHM VPS (`vps.pomac.info`, AlmaLinux 9, Apache + LSAPI) and connect a
consuming backend running on a **different** server.

```
 Other project (server A)                       Feed microservice (vps.pomac.info)
 ───────────────────────                        ─────────────────────────────────
 post created/updated/deleted ── PUT/DELETE ──▶ https://feed.pomac.info/internal/posts/{id}
 app feed screen              ── GET ─────────▶ https://feed.pomac.info/internal/feed
                                 (HMAC-signed, shared INTERNAL_API_SECRET)
```

The examples below use the subdomain `feed.pomac.info`, the cPanel account `pomacinf` and the app directory
`/home/pomacinf/feed-microservice`. Change them as needed.

## 1. Server prerequisites (as root, once)

**PHP 8.3+**: WHM → *EasyApache 4* → *Customize*. Make sure `ea-php83` is installed with these extensions:
`bcmath`, `ctype`, `curl`, `fileinfo`, `intl`, `mbstring`, `mysqlnd`, `pdo`, `posix`, `tokenizer`, `xml`, `zip`.
To check:

```bash
/opt/cpanel/ea-php83/root/usr/bin/php -v
/opt/cpanel/ea-php83/root/usr/bin/php -m
```

**Redis** (cache, HMAC replay protection, rate limiting). The app uses `predis`, so no PHP extension is needed:

```bash
dnf install -y redis
systemctl enable --now redis
redis-cli ping            # → PONG
```

Redis listens on `127.0.0.1` only by default. Keep it that way.

## 2. cPanel setup (account `pomacinf`)

1. **Domains** → *Create A New Domain*: `feed.pomac.info`, with the document root set to
   `/home/pomacinf/feed-microservice/public`. Untick "Share document root". The document root must be
   `public/` and never the project folder, or `.env` becomes downloadable.
2. **MultiPHP Manager**: set `feed.pomac.info` to **PHP 8.3**.
3. **MySQL® Databases**: create database `pomacinf_feed` and user `pomacinf_feed`, then add the user to the
   database with **ALL PRIVILEGES**.
4. **SSL/TLS Status**: run AutoSSL for `feed.pomac.info`. Wait until DNS for the subdomain resolves to this
   server first.

## 3. Get the code onto the server (SSH as `pomacinf`, not root)

The repository ([ahmedhessuin27/social-microservice](https://github.com/ahmedhessuin27/social-microservice))
is public, so no SSH key is needed to clone or pull:

```bash
git clone https://github.com/ahmedhessuin27/social-microservice.git ~/feed-microservice
cd ~/feed-microservice
```

If the repository is made private later, give the server a read-only deploy key and switch the remote to SSH:

```bash
ssh-keygen -t ed25519 -f ~/.ssh/feed_deploy -N ""
cat ~/.ssh/feed_deploy.pub    # GitHub → ahmedhessuin27/social-microservice → Settings → Deploy keys → Add
cat >> ~/.ssh/config <<'EOF'
Host github-social
    HostName github.com
    IdentityFile ~/.ssh/feed_deploy
EOF

git remote set-url origin git@github-social:ahmedhessuin27/social-microservice.git
```

Use the cPanel PHP 8.3 binary explicitly. The default `php` on the PATH may be a different version:

```bash
alias php83=/opt/cpanel/ea-php83/root/usr/bin/php
php83 /opt/cpanel/composer/bin/composer install --no-dev --optimize-autoloader
```

## 4. Configure `.env`

```bash
cp .env.example .env
php83 artisan key:generate
openssl rand -hex 32          # → INTERNAL_API_SECRET (give the same value to the other project)
```

Edit `.env` and change these values:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://feed.pomac.info
LOG_LEVEL=warning

DB_DATABASE=pomacinf_feed
DB_USERNAME=pomacinf_feed
DB_PASSWORD=...

CACHE_STORE=redis
QUEUE_CONNECTION=redis

INTERNAL_API_SECRET=...       # the openssl value above
INTERNAL_API_RATE_LIMIT=600   # all calls come from the other project's single server IP
```

Then:

```bash
php83 artisan migrate --force
php83 artisan optimize
```

## 5. Check it works

```bash
curl -i https://feed.pomac.info/up                   # → 200
curl -i https://feed.pomac.info/internal/feed        # → 401 (unsigned requests are rejected; that's correct)
```

## 6. Updating later

```bash
cd ~/feed-microservice
git pull
php83 /opt/cpanel/composer/bin/composer install --no-dev --optimize-autoloader
php83 artisan migrate --force
php83 artisan optimize
```

## 7. Connect the other project

### Requirements on the other server

- **Clocks in sync.** Signed requests are rejected if the two servers' clocks differ by more than 300 seconds.
  Compare `date -u` on both machines.
- **Outbound HTTPS** to `feed.pomac.info:443`.
- **Ids are integers.** Every id sent (post, comment, like, user) is the other project's own integer id.

### `.env` and `config/services.php`

```dotenv
FEED_SERVICE_URL=https://feed.pomac.info
FEED_SERVICE_SECRET=...   # exactly the same as INTERNAL_API_SECRET on the feed server
```

```php
'feed' => [
    'url' => env('FEED_SERVICE_URL'),
    'secret' => env('FEED_SERVICE_SECRET'),
],
```

### Client class (`app/Services/FeedClient.php`)

```php
<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class FeedClient
{
    public function syncPost(int $id, array $payload): Response
    {
        return $this->send('PUT', "posts/{$id}", $payload);
    }

    public function deletePost(int $id): Response
    {
        return $this->send('DELETE', "posts/{$id}");
    }

    public function syncComment(int $id, array $payload): Response
    {
        return $this->send('PUT', "comments/{$id}", $payload);
    }

    public function deleteComment(int $id): Response
    {
        return $this->send('DELETE', "comments/{$id}");
    }

    public function syncLike(int $id, array $payload): Response
    {
        return $this->send('PUT', "likes/{$id}", $payload);
    }

    public function deleteLike(int $id): Response
    {
        return $this->send('DELETE', "likes/{$id}");
    }

    /**
     * Every author's published posts, newest first. Pass the previous
     * response's next_cursor to get the next page.
     */
    public function feed(int $perPage = 15, ?string $cursor = null): array
    {
        return $this->send('GET', 'feed', null, array_filter([
            'per_page' => $perPage,
            'cursor' => $cursor,
        ]))->json();
    }

    private function send(string $method, string $path, ?array $payload = null, array $query = []): Response
    {
        // Signed string: METHOD\nPATH\nTIMESTAMP\nBODY. PATH has no leading slash and no query string.
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

        return $request->send($method, $path, ['query' => $query])->throw();
    }
}
```

### When to call it

Call `syncPost()` whenever a post is created or updated, and `deletePost()` when it is deleted. A model
observer, ideally running in a queued job so a slow network never blocks the user, works well:

```php
app(FeedClient::class)->syncPost($post->id, [
    'author_id' => $post->user_id,
    'content' => $post->content,
    'type' => 'text',                 // text | image | video | multi_image
    'status' => 'published',          // published | draft | hidden; only published posts appear in feeds
    'likes_count' => $post->likes_count,
    'comments_count' => $post->comments_count,
    'reposts_count' => 0,
    'reposted_from_id' => null,
    'posted_at' => $post->created_at->toIso8601String(),
    'media' => [['url' => 'https://.../image.jpg', 'position' => 0]],  // optional, public URLs
    'hashtags' => ['laravel'],                                         // optional, without '#'
]);
```

To read the feed, call `app(FeedClient::class)->feed()`. Posts come back with `author_id` only, so attach the
author's name and avatar from the other project's own `users` table before returning them to the app.

**Existing data:** posts created before the integration was switched on are not in the feed yet. Backfill
them once by looping over the existing posts and calling `syncPost()` for each. Syncing is idempotent, so
running the backfill twice is harmless.
