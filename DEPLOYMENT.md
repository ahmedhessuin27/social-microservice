# Deployment

How this service is deployed on the cPanel/WHM VPS (`vps.pomac.info`, AlmaLinux 9, Apache + LSAPI), and how a
consuming Laravel app on a **different** server connects to it.

```
 Mobile/web app ──login──▶ Other project (server A)            Feed microservice (this VPS)
                           routes/api.php: /api/social/...      https://socialmedia.pomac.info
                           SocialController + FeedClient ─────▶ /internal/...  (HMAC-signed, shared secret)
                           (adds the logged-in user's id)       stores posts, comments, likes, counts
```

| | |
|---|---|
| URL | `https://socialmedia.pomac.info` |
| Code | `/home/pomacinf/public_html/socialmedia` (cPanel account `pomacinf`) |
| Document root | `/home/pomacinf/public_html/socialmedia/public` |
| Repository | <https://github.com/ahmedhessuin27/social-microservice> (public) |
| Database | `pomacinf_feed_db`, user `pomacinf_pomacinf_feed` |
| DNS | Cloudflare (`pomac.info` zone), A record `socialmedia` → the VPS IP |

## 1. Server prerequisites (root, once)

- **PHP 8.3** (`ea-php83`) with `bcmath`, `ctype`, `curl`, `fileinfo`, `intl`, `mbstring`, `mysqlnd`, `pdo`,
  `posix`, `tokenizer`, `xml`, `zip` (WHM → *EasyApache 4*). Check: `/opt/cpanel/ea-php83/root/usr/bin/php -m`.
- **Redis**: `dnf install -y redis && systemctl enable --now redis`, then `redis-cli ping` → `PONG`. Other
  projects share it, so this service uses Redis databases `2` and `3` (see `.env` below).
- **Composer** for `pomacinf`, once:
  ```bash
  su -s /bin/bash - pomacinf
  php83() { /opt/cpanel/ea-php83/root/usr/bin/php "$@"; }
  php83 -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
  php83 composer-setup.php --install-dir=$HOME/bin --filename=composer && rm composer-setup.php
  ```

## 2. Protect `public_html`

Several Laravel projects live inside `public_html`, which is also `pomac.info`'s web root, so their `.env`
files were downloadable at `pomac.info/<project>/.env`. This block at the **top** of
`/home/pomacinf/public_html/.htaccess` blocks that for every project, and blocks this one entirely through
`pomac.info`:

```apache
# Never serve .env / .git files from any project under public_html
RedirectMatch 404 /\.(env|git)
<FilesMatch "^\.(env|git)">
    Require all denied
</FilesMatch>
# socialmedia is only served via socialmedia.pomac.info, never via pomac.info/socialmedia/
RedirectMatch 404 ^/socialmedia/
```

Check from anywhere: `curl -I https://pomac.info/socialmedia/.env` must return 403 or 404, never 200.

## 3. Subdomain, PHP version, DNS, SSL

1. **cPanel → Domains → Create A New Domain**: `socialmedia.pomac.info`, *Share document root* unticked.
   Then **Manage** → document root `public_html/socialmedia/public`.
2. **cPanel → MultiPHP Manager**: `socialmedia.pomac.info` → **PHP 8.3**. cPanel records this in
   `public/.htaccess`, so `git status` shows that file as modified — expected; don't commit or revert it.
3. **Cloudflare → pomac.info → DNS**: A record `socialmedia` → the VPS IP (`curl -s ifconfig.me` on the VPS).
   Prefer **DNS only** (grey cloud) for a server-to-server API: behind the proxy, every request appears to
   come from Cloudflare's IPs, which makes the per-IP rate limit meaningless.
4. **cPanel → SSL/TLS Status** → *Run AutoSSL* for `socialmedia.pomac.info` (needed for HTTPS with the grey cloud).

## 4. First install (as `pomacinf`)

```bash
su -s /bin/bash - pomacinf
php83() { /opt/cpanel/ea-php83/root/usr/bin/php "$@"; }
git clone https://github.com/ahmedhessuin27/social-microservice.git ~/public_html/socialmedia
cd ~/public_html/socialmedia
php83 ~/bin/composer install --no-dev --optimize-autoloader
cp .env.example .env
php83 artisan key:generate
openssl rand -hex 32          # → INTERNAL_API_SECRET; keep it private
```

Change these in `.env`:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://socialmedia.pomac.info
LOG_LEVEL=warning

DB_DATABASE=pomacinf_feed_db
DB_USERNAME=pomacinf_pomacinf_feed
DB_PASSWORD="..."

CACHE_STORE=redis
QUEUE_CONNECTION=redis
REDIS_DB=2
REDIS_CACHE_DB=3

INTERNAL_API_SECRET=...       # the openssl value; the other project needs exactly the same value
INTERNAL_API_RATE_LIMIT=6000  # per minute, per IP — all app traffic arrives from the other project's one IP
```

Then:

```bash
php83 artisan migrate --force
php83 artisan optimize
```

Check: `https://socialmedia.pomac.info/up` → 200, and `https://socialmedia.pomac.info/internal/feed` → 401
(unsigned requests are rejected — that's correct).

## 5. Updating

As root:

```bash
cd /home/pomacinf/public_html/socialmedia
git pull
/opt/cpanel/ea-php83/root/usr/bin/php /home/pomacinf/bin/composer install --no-dev --optimize-autoloader
/opt/cpanel/ea-php83/root/usr/bin/php artisan migrate --force
/opt/cpanel/ea-php83/root/usr/bin/php artisan optimize
chown -R pomacinf:pomacinf /home/pomacinf/public_html/socialmedia
```

The last line matters: files root creates are owned by root, and the site (which runs as `pomacinf`) then
can't write its logs — 500 errors. Root needs, once,
`git config --global --add safe.directory /home/pomacinf/public_html/socialmedia` to run `git pull` here.

After any `.env` change, run `php artisan optimize` again — the configuration is cached.

## 6. Connect the other project

Nothing about posts lives in the other project — no Post model, no posts table. It only needs:

**`.env`**

```dotenv
FEED_SERVICE_URL=https://socialmedia.pomac.info
FEED_SERVICE_SECRET=...   # exactly the same as INTERNAL_API_SECRET on the feed server
```

**`config/services.php`**, inside `return [ ... ]`:

```php
'feed' => [
    'url' => env('FEED_SERVICE_URL'),
    'secret' => env('FEED_SERVICE_SECRET'),
],
```

**Three files from this repository's [client/](client/) folder:**

| Copy | To | What it is |
|---|---|---|
| [client/FeedClient.php](client/FeedClient.php) | `app/Services/FeedClient.php` | Signs and sends every call to the microservice |
| [client/SocialController.php](client/SocialController.php) | `app/Http/Controllers/SocialController.php` | The app-facing endpoints; adds the logged-in user's id and each author's name |
| [client/routes.php](client/routes.php) | append to `routes/api.php` (without the `<?php` line) | `/api/social/...` routes, behind the app's login |

Adjust two things to the other project:
- the login middleware in `routes.php` (`auth:sanctum`) to whatever its API uses;
- the user columns in `SocialController::withAuthors()` (`id`, `name`) — e.g. add `avatar`.

Then clear its config cache (`php artisan config:clear`, or redeploy).

### What the app calls

All under the other project's `/api/social` prefix, with the app's normal login token:

| App call | Body / query |
|---|---|
| `GET feed` | `?cursor=` for the next page |
| `GET users/{userId}/posts` | `?cursor=` |
| `POST posts` | `content`, `type` (`text`/`image`/`video`/`multi_image`), optional `status`, `media` (`[{url}]`), `hashtags`, `reposted_from_id` |
| `GET` / `PUT` / `DELETE posts/{id}` | `PUT`: any of `content`, `type`, `status`, `media`, `hashtags` |
| `GET posts/{id}/comments` | `?parent_id=` for replies, `?cursor=` |
| `POST posts/{id}/comments` | `text`, optional `parent_id` |
| `PUT` / `DELETE comments/{id}` | `PUT`: `text` |
| `POST` / `DELETE posts/{id}/like` | — |
| `POST` / `DELETE comments/{id}/like` | — |

Responses keep the microservice's shape (`status`, `message`, `data`, and for lists `next_cursor`), plus an
`author` object on each post/comment, and a `liked` flag for the logged-in user.

### Checking the connection without SSH

Call `GET /api/social/feed` on the other project with a logged-in user's token (Postman, or the app):

| Result | Cause |
|---|---|
| `200` with `"data": []` | Connected — no posts yet |
| `401` "Invalid HMAC signature" | `FEED_SERVICE_SECRET` differs from the feed server's `INTERNAL_API_SECRET`, or its config cache is stale |
| `401` "expired" | The two servers' clocks differ by more than 5 minutes |
| `401` "Unauthenticated." | The app's own login — not the microservice |
| `500` / timeout | Wrong `FEED_SERVICE_URL`, or the other server can't reach `socialmedia.pomac.info` |
