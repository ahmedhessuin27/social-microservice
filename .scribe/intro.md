# Introduction

Standalone, reusable Feed Microservice — internal APIs for syncing posts/comments/likes/follows and reading a merged feed. Not tied to any specific backend.

<aside>
    <strong>Base URL</strong>: <code>http://localhost</code>
</aside>

    This documentation covers the internal API of the Feed Microservice: idempotent sync endpoints
    (posts, comments, likes, follows) that any Laravel backend can call to mirror its own data here,
    plus a read endpoint that returns a merged, paginated, newest-first feed.

    <aside>Every endpoint below requires HMAC authentication — see the <b>Authentication</b> section for
    the exact header names and how to compute the signature.</aside>

    ## Authentication

    Every request must include two headers, computed with a secret shared out-of-band between this
    service and the calling backend (`INTERNAL_API_SECRET`):

    - `X-Internal-Timestamp`: the current Unix timestamp (seconds).
    - `X-Internal-Signature`: `hash_hmac('sha256', canonical, secret)`.

    The canonical string to sign is:

    ```
    {METHOD}\n{PATH}\n{TIMESTAMP}\n{RAW_BODY}
    ```

    - `METHOD` is uppercase (`PUT`, `DELETE`, `GET`).
    - `PATH` has no leading slash and no query string, e.g. `internal/posts/{id}`.
    - `TIMESTAMP` is the exact same value sent in the `X-Internal-Timestamp` header.
    - `RAW_BODY` is the exact raw request body bytes (empty string for a bodyless request).

    Requests are rejected if the timestamp is more than `INTERNAL_API_TIMESTAMP_TOLERANCE` seconds
    (default 300) away from the server's clock, or if the exact same signature has already been seen
    within `INTERNAL_API_REPLAY_TTL` seconds (default 600) — replay protection.

