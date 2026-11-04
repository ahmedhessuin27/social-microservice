<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Laravel API Documentation</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.style.css") }}" media="screen">
    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.print.css") }}" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>

    <link rel="stylesheet"
          href="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/highlight.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    <style id="language-style">
        /* starts out as display none and is replaced with js later  */
                    body .content .bash-example code { display: none; }
                    body .content .javascript-example code { display: none; }
            </style>

    <script>
        var tryItOutBaseUrl = "http://localhost";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset("/vendor/scribe/js/tryitout-5.11.0.js") }}"></script>

    <script src="{{ asset("/vendor/scribe/js/theme-default-5.11.0.js") }}"></script>

</head>

<body data-languages="[&quot;bash&quot;,&quot;javascript&quot;]">

<a href="#" id="nav-button">
    <span>
        MENU
        <img src="{{ asset("/vendor/scribe/images/navbar.png") }}" alt="navbar-image"/>
    </span>
</a>
<div class="tocify-wrapper">
    
            <div class="lang-selector">
                                            <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                            <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                    </div>
    
    <div class="search">
        <input type="text" class="search" id="input-search" placeholder="Search">
    </div>

    <div id="toc">
                    <ul id="tocify-header-introduction" class="tocify-header">
                <li class="tocify-item level-1" data-unique="introduction">
                    <a href="#introduction">Introduction</a>
                </li>
                            </ul>
                    <ul id="tocify-header-authenticating-requests" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authenticating-requests">
                    <a href="#authenticating-requests">Authenticating requests</a>
                </li>
                            </ul>
                    <ul id="tocify-header-comments" class="tocify-header">
                <li class="tocify-item level-1" data-unique="comments">
                    <a href="#comments">Comments</a>
                </li>
                                    <ul id="tocify-subheader-comments" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="comments-PUTinternal-comments--id-">
                                <a href="#comments-PUTinternal-comments--id-">Sync a comment</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="comments-DELETEinternal-comments--id-">
                                <a href="#comments-DELETEinternal-comments--id-">Delete a comment</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-feed" class="tocify-header">
                <li class="tocify-item level-1" data-unique="feed">
                    <a href="#feed">Feed</a>
                </li>
                                    <ul id="tocify-subheader-feed" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="feed-GETinternal-feed">
                                <a href="#feed-GETinternal-feed">Get the global feed</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="feed-GETinternal-feed--userId-">
                                <a href="#feed-GETinternal-feed--userId-">Get a user's feed</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-follows" class="tocify-header">
                <li class="tocify-item level-1" data-unique="follows">
                    <a href="#follows">Follows</a>
                </li>
                                    <ul id="tocify-subheader-follows" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="follows-PUTinternal-follows">
                                <a href="#follows-PUTinternal-follows">Sync a follow relationship</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="follows-DELETEinternal-follows">
                                <a href="#follows-DELETEinternal-follows">Delete a follow relationship</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-likes" class="tocify-header">
                <li class="tocify-item level-1" data-unique="likes">
                    <a href="#likes">Likes</a>
                </li>
                                    <ul id="tocify-subheader-likes" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="likes-PUTinternal-likes--id-">
                                <a href="#likes-PUTinternal-likes--id-">Sync a like</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="likes-DELETEinternal-likes--id-">
                                <a href="#likes-DELETEinternal-likes--id-">Delete a like</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-posts" class="tocify-header">
                <li class="tocify-item level-1" data-unique="posts">
                    <a href="#posts">Posts</a>
                </li>
                                    <ul id="tocify-subheader-posts" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="posts-PUTinternal-posts--id-">
                                <a href="#posts-PUTinternal-posts--id-">Sync a post</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="posts-DELETEinternal-posts--id-">
                                <a href="#posts-DELETEinternal-posts--id-">Delete a post</a>
                            </li>
                                                                        </ul>
                            </ul>
            </div>

    <ul class="toc-footer" id="toc-footer">
                    <li style="padding-bottom: 5px;"><a href="{{ route("scribe.postman") }}">View Postman collection</a></li>
                            <li style="padding-bottom: 5px;"><a href="{{ route("scribe.openapi") }}">View OpenAPI spec</a></li>
                <li><a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a></li>
    </ul>

    <ul class="toc-footer" id="last-updated">
        <li>Last updated: November 4, 2026</li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="introduction">Introduction</h1>
<p>Standalone, reusable Feed Microservice — internal APIs for syncing posts/comments/likes/follows and reading a merged feed. Not tied to any specific backend.</p>
<aside>
    <strong>Base URL</strong>: <code>http://localhost</code>
</aside>
<pre><code>This documentation covers the internal API of the Feed Microservice: idempotent sync endpoints
(posts, comments, likes, follows) that any Laravel backend can call to mirror its own data here,
plus a read endpoint that returns a merged, paginated, newest-first feed.

&lt;aside&gt;Every endpoint below requires HMAC authentication — see the &lt;b&gt;Authentication&lt;/b&gt; section for
the exact header names and how to compute the signature.&lt;/aside&gt;

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
within `INTERNAL_API_REPLAY_TTL` seconds (default 600) — replay protection.</code></pre>

        <h1 id="authenticating-requests">Authenticating requests</h1>
<p>This API is not authenticated.</p>

        <h1 id="comments">Comments</h1>

    <p>Sync a consuming backend's post comments into the feed microservice's own copy.</p>

                                <h2 id="comments-PUTinternal-comments--id-">Sync a comment</h2>

<p>
</p>

<p>Idempotent upsert. The parent post must already be synced — if it
isn't yet, this fails on the foreign key and should be retried.</p>

<span id="example-requests-PUTinternal-comments--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost/internal/comments/2001" \
    --header "X-Internal-Timestamp: required Unix timestamp used in the HMAC signature. Example: 1735689600" \
    --header "X-Internal-Signature: required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"post_id\": 1001,
    \"author_id\": 42,
    \"text\": \"Nice post!\",
    \"likes_count\": 0,
    \"replies_count\": 0
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/internal/comments/2001"
);

const headers = {
    "X-Internal-Timestamp": "required Unix timestamp used in the HMAC signature. Example: 1735689600",
    "X-Internal-Signature": "required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "post_id": 1001,
    "author_id": 42,
    "text": "Nice post!",
    "likes_count": 0,
    "replies_count": 0
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTinternal-comments--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 200,
    &quot;message&quot;: &quot;Comment synced successfully.&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 422,
    &quot;message&quot;: &quot;The given data was invalid.&quot;,
    &quot;error&quot;: {
        &quot;post_id&quot;: [
            &quot;The post id field is required.&quot;
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-PUTinternal-comments--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTinternal-comments--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTinternal-comments--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTinternal-comments--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTinternal-comments--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTinternal-comments--id-" data-method="PUT"
      data-path="internal/comments/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTinternal-comments--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTinternal-comments--id-"
                    onclick="tryItOut('PUTinternal-comments--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTinternal-comments--id-"
                    onclick="cancelTryOut('PUTinternal-comments--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTinternal-comments--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>internal/comments/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>X-Internal-Timestamp</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="X-Internal-Timestamp"                data-endpoint="PUTinternal-comments--id-"
               value="required Unix timestamp used in the HMAC signature. Example: 1735689600"
               data-component="header">
    <br>
<p>Example: <code>required Unix timestamp used in the HMAC signature. Example: 1735689600</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>X-Internal-Signature</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="X-Internal-Signature"                data-endpoint="PUTinternal-comments--id-"
               value="required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9"
               data-component="header">
    <br>
<p>Example: <code>required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTinternal-comments--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTinternal-comments--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="PUTinternal-comments--id-"
               value="2001"
               data-component="url">
    <br>
<p>The comment's id, as used by the calling backend. Example: <code>2001</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>post_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="post_id"                data-endpoint="PUTinternal-comments--id-"
               value="1001"
               data-component="body">
    <br>
<p>The id of the post this comment belongs to. Must already be synced. Must be at least 1. Example: <code>1001</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>author_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="author_id"                data-endpoint="PUTinternal-comments--id-"
               value="42"
               data-component="body">
    <br>
<p>The comment author's id, as used by the calling backend. Must be at least 1. Example: <code>42</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>text</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="text"                data-endpoint="PUTinternal-comments--id-"
               value="Nice post!"
               data-component="body">
    <br>
<p>The comment text. Example: <code>Nice post!</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>parent_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="parent_id"                data-endpoint="PUTinternal-comments--id-"
               value=""
               data-component="body">
    <br>
<p>If this is a reply, the parent comment's id. Must be at least 1.</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>likes_count</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="likes_count"                data-endpoint="PUTinternal-comments--id-"
               value="0"
               data-component="body">
    <br>
<p>Current like count, as tracked by the calling backend. Must be at least 0. Example: <code>0</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>replies_count</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="replies_count"                data-endpoint="PUTinternal-comments--id-"
               value="0"
               data-component="body">
    <br>
<p>Current reply count, as tracked by the calling backend. Must be at least 0. Example: <code>0</code></p>
        </div>
        </form>

                    <h2 id="comments-DELETEinternal-comments--id-">Delete a comment</h2>

<p>
</p>

<p>Idempotent: returns 200 whether or not the comment existed locally.</p>

<span id="example-requests-DELETEinternal-comments--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost/internal/comments/2001" \
    --header "X-Internal-Timestamp: required Unix timestamp used in the HMAC signature. Example: 1735689600" \
    --header "X-Internal-Signature: required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/internal/comments/2001"
);

const headers = {
    "X-Internal-Timestamp": "required Unix timestamp used in the HMAC signature. Example: 1735689600",
    "X-Internal-Signature": "required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEinternal-comments--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 200,
    &quot;message&quot;: &quot;Comment deleted successfully.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-DELETEinternal-comments--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEinternal-comments--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEinternal-comments--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEinternal-comments--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEinternal-comments--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEinternal-comments--id-" data-method="DELETE"
      data-path="internal/comments/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEinternal-comments--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEinternal-comments--id-"
                    onclick="tryItOut('DELETEinternal-comments--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEinternal-comments--id-"
                    onclick="cancelTryOut('DELETEinternal-comments--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEinternal-comments--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>internal/comments/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>X-Internal-Timestamp</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="X-Internal-Timestamp"                data-endpoint="DELETEinternal-comments--id-"
               value="required Unix timestamp used in the HMAC signature. Example: 1735689600"
               data-component="header">
    <br>
<p>Example: <code>required Unix timestamp used in the HMAC signature. Example: 1735689600</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>X-Internal-Signature</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="X-Internal-Signature"                data-endpoint="DELETEinternal-comments--id-"
               value="required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9"
               data-component="header">
    <br>
<p>Example: <code>required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEinternal-comments--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEinternal-comments--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="DELETEinternal-comments--id-"
               value="2001"
               data-component="url">
    <br>
<p>The comment's id, as used by the calling backend. Example: <code>2001</code></p>
            </div>
                    </form>

                <h1 id="feed">Feed</h1>

    <p>Read published posts, newest first: either every author's posts (for
consumers with no follow system) or one user's merged feed (own posts +
followed users' posts).</p>

                                <h2 id="feed-GETinternal-feed">Get the global feed</h2>

<p>
</p>

<p>Returns every author's published posts, newest first, cursor-paginated.
Use this when the calling backend has no follow/following system.
Draft/hidden posts are excluded.</p>

<span id="example-requests-GETinternal-feed">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/internal/feed?per_page=15" \
    --header "X-Internal-Timestamp: required Unix timestamp used in the HMAC signature. Example: 1735689600" \
    --header "X-Internal-Signature: required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"per_page\": 1
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/internal/feed"
);

const params = {
    "per_page": "15",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "X-Internal-Timestamp": "required Unix timestamp used in the HMAC signature. Example: 1735689600",
    "X-Internal-Signature": "required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "per_page": 1
};

fetch(url, {
    method: "GET",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETinternal-feed">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 200,
    &quot;message&quot;: &quot;Feed retrieved successfully.&quot;,
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1001,
            &quot;author_id&quot;: 42,
            &quot;content&quot;: &quot;Hello world&quot;,
            &quot;type&quot;: &quot;text&quot;,
            &quot;status&quot;: &quot;published&quot;,
            &quot;likes_count&quot;: 3,
            &quot;comments_count&quot;: 1,
            &quot;reposts_count&quot;: 0,
            &quot;reposted_from_id&quot;: null,
            &quot;posted_at&quot;: &quot;2026-08-06T12:00:00.000000Z&quot;,
            &quot;media&quot;: [],
            &quot;hashtags&quot;: [
                &quot;laravel&quot;
            ]
        }
    ],
    &quot;next_cursor&quot;: &quot;eyJwb3N0ZWRfYXQiOiIyMDI2LTA4LTA2IDEyOjAwOjAwIn0&quot;,
    &quot;prev_cursor&quot;: null,
    &quot;per_page&quot;: 15,
    &quot;links&quot;: {
        &quot;first&quot;: null,
        &quot;last&quot;: null,
        &quot;prev&quot;: null,
        &quot;next&quot;: &quot;http://localhost/internal/feed?cursor=eyJwb3N0ZWRfYXQiOiIyMDI2LTA4LTA2IDEyOjAwOjAwIn0&quot;
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETinternal-feed" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETinternal-feed"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETinternal-feed"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETinternal-feed" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETinternal-feed">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETinternal-feed" data-method="GET"
      data-path="internal/feed"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETinternal-feed', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETinternal-feed"
                    onclick="tryItOut('GETinternal-feed');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETinternal-feed"
                    onclick="cancelTryOut('GETinternal-feed');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETinternal-feed"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>internal/feed</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>X-Internal-Timestamp</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="X-Internal-Timestamp"                data-endpoint="GETinternal-feed"
               value="required Unix timestamp used in the HMAC signature. Example: 1735689600"
               data-component="header">
    <br>
<p>Example: <code>required Unix timestamp used in the HMAC signature. Example: 1735689600</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>X-Internal-Signature</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="X-Internal-Signature"                data-endpoint="GETinternal-feed"
               value="required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9"
               data-component="header">
    <br>
<p>Example: <code>required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETinternal-feed"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETinternal-feed"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETinternal-feed"
               value="15"
               data-component="query">
    <br>
<p>Number of posts per page (1-50). Defaults to 15. Example: <code>15</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETinternal-feed"
               value="1"
               data-component="body">
    <br>
<p>Must be at least 1. Must not be greater than 50. Example: <code>1</code></p>
        </div>
        </form>

                    <h2 id="feed-GETinternal-feed--userId-">Get a user&#039;s feed</h2>

<p>
</p>

<p>Returns the user's own posts and their followed users' posts, newest
first, cursor-paginated. Draft/hidden posts are excluded.</p>

<span id="example-requests-GETinternal-feed--userId-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/internal/feed/42?per_page=15" \
    --header "X-Internal-Timestamp: required Unix timestamp used in the HMAC signature. Example: 1735689600" \
    --header "X-Internal-Signature: required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"per_page\": 1
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/internal/feed/42"
);

const params = {
    "per_page": "15",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "X-Internal-Timestamp": "required Unix timestamp used in the HMAC signature. Example: 1735689600",
    "X-Internal-Signature": "required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "per_page": 1
};

fetch(url, {
    method: "GET",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETinternal-feed--userId-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 200,
    &quot;message&quot;: &quot;Feed retrieved successfully.&quot;,
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1001,
            &quot;author_id&quot;: 42,
            &quot;content&quot;: &quot;Hello world&quot;,
            &quot;type&quot;: &quot;text&quot;,
            &quot;status&quot;: &quot;published&quot;,
            &quot;likes_count&quot;: 3,
            &quot;comments_count&quot;: 1,
            &quot;reposts_count&quot;: 0,
            &quot;reposted_from_id&quot;: null,
            &quot;posted_at&quot;: &quot;2026-08-06T12:00:00.000000Z&quot;,
            &quot;media&quot;: [],
            &quot;hashtags&quot;: [
                &quot;laravel&quot;
            ]
        }
    ],
    &quot;next_cursor&quot;: &quot;eyJwb3N0ZWRfYXQiOiIyMDI2LTA4LTA2IDEyOjAwOjAwIn0&quot;,
    &quot;prev_cursor&quot;: null,
    &quot;per_page&quot;: 15,
    &quot;links&quot;: {
        &quot;first&quot;: null,
        &quot;last&quot;: null,
        &quot;prev&quot;: null,
        &quot;next&quot;: &quot;http://localhost/internal/feed/42?cursor=eyJwb3N0ZWRfYXQiOiIyMDI2LTA4LTA2IDEyOjAwOjAwIn0&quot;
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETinternal-feed--userId-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETinternal-feed--userId-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETinternal-feed--userId-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETinternal-feed--userId-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETinternal-feed--userId-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETinternal-feed--userId-" data-method="GET"
      data-path="internal/feed/{userId}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETinternal-feed--userId-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETinternal-feed--userId-"
                    onclick="tryItOut('GETinternal-feed--userId-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETinternal-feed--userId-"
                    onclick="cancelTryOut('GETinternal-feed--userId-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETinternal-feed--userId-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>internal/feed/{userId}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>X-Internal-Timestamp</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="X-Internal-Timestamp"                data-endpoint="GETinternal-feed--userId-"
               value="required Unix timestamp used in the HMAC signature. Example: 1735689600"
               data-component="header">
    <br>
<p>Example: <code>required Unix timestamp used in the HMAC signature. Example: 1735689600</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>X-Internal-Signature</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="X-Internal-Signature"                data-endpoint="GETinternal-feed--userId-"
               value="required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9"
               data-component="header">
    <br>
<p>Example: <code>required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETinternal-feed--userId-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETinternal-feed--userId-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>userId</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="userId"                data-endpoint="GETinternal-feed--userId-"
               value="42"
               data-component="url">
    <br>
<p>The id of the user whose feed to build, as used by the calling backend. Example: <code>42</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETinternal-feed--userId-"
               value="15"
               data-component="query">
    <br>
<p>Number of posts per page (1-50). Defaults to 15. Example: <code>15</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETinternal-feed--userId-"
               value="1"
               data-component="body">
    <br>
<p>Must be at least 1. Must not be greater than 50. Example: <code>1</code></p>
        </div>
        </form>

                <h1 id="follows">Follows</h1>

    <p>Sync a consuming backend's follow relationships into the feed microservice's own copy.
Unlike posts/comments/likes, follows have no path {id} — they're keyed by
the (follower_id, following_id) pair, sent in the request body for both endpoints.</p>

                                <h2 id="follows-PUTinternal-follows">Sync a follow relationship</h2>

<p>
</p>

<p>Idempotent: calling this twice with the same pair has no additional effect.</p>

<span id="example-requests-PUTinternal-follows">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost/internal/follows" \
    --header "X-Internal-Timestamp: required Unix timestamp used in the HMAC signature. Example: 1735689600" \
    --header "X-Internal-Signature: required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"follower_id\": 42,
    \"following_id\": 43
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/internal/follows"
);

const headers = {
    "X-Internal-Timestamp": "required Unix timestamp used in the HMAC signature. Example: 1735689600",
    "X-Internal-Signature": "required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "follower_id": 42,
    "following_id": 43
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTinternal-follows">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 200,
    &quot;message&quot;: &quot;Follow relationship synced successfully.&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 422,
    &quot;message&quot;: &quot;The given data was invalid.&quot;,
    &quot;error&quot;: {
        &quot;following_id&quot;: [
            &quot;The following id and follower id must be different.&quot;
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-PUTinternal-follows" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTinternal-follows"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTinternal-follows"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTinternal-follows" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTinternal-follows">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTinternal-follows" data-method="PUT"
      data-path="internal/follows"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTinternal-follows', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTinternal-follows"
                    onclick="tryItOut('PUTinternal-follows');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTinternal-follows"
                    onclick="cancelTryOut('PUTinternal-follows');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTinternal-follows"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>internal/follows</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>X-Internal-Timestamp</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="X-Internal-Timestamp"                data-endpoint="PUTinternal-follows"
               value="required Unix timestamp used in the HMAC signature. Example: 1735689600"
               data-component="header">
    <br>
<p>Example: <code>required Unix timestamp used in the HMAC signature. Example: 1735689600</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>X-Internal-Signature</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="X-Internal-Signature"                data-endpoint="PUTinternal-follows"
               value="required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9"
               data-component="header">
    <br>
<p>Example: <code>required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTinternal-follows"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTinternal-follows"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>follower_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="follower_id"                data-endpoint="PUTinternal-follows"
               value="42"
               data-component="body">
    <br>
<p>The following user's id, as used by the calling backend. Must be at least 1. Example: <code>42</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>following_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="following_id"                data-endpoint="PUTinternal-follows"
               value="43"
               data-component="body">
    <br>
<p>The followed user's id, as used by the calling backend. The value and <code>follower_id</code> must be different. Must be at least 1. Example: <code>43</code></p>
        </div>
        </form>

                    <h2 id="follows-DELETEinternal-follows">Delete a follow relationship</h2>

<p>
</p>

<p>Idempotent: returns 200 whether or not the relationship existed locally.</p>

<span id="example-requests-DELETEinternal-follows">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost/internal/follows" \
    --header "X-Internal-Timestamp: required Unix timestamp used in the HMAC signature. Example: 1735689600" \
    --header "X-Internal-Signature: required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"follower_id\": 42,
    \"following_id\": 43
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/internal/follows"
);

const headers = {
    "X-Internal-Timestamp": "required Unix timestamp used in the HMAC signature. Example: 1735689600",
    "X-Internal-Signature": "required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "follower_id": 42,
    "following_id": 43
};

fetch(url, {
    method: "DELETE",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEinternal-follows">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 200,
    &quot;message&quot;: &quot;Follow relationship deleted successfully.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-DELETEinternal-follows" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEinternal-follows"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEinternal-follows"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEinternal-follows" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEinternal-follows">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEinternal-follows" data-method="DELETE"
      data-path="internal/follows"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEinternal-follows', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEinternal-follows"
                    onclick="tryItOut('DELETEinternal-follows');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEinternal-follows"
                    onclick="cancelTryOut('DELETEinternal-follows');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEinternal-follows"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>internal/follows</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>X-Internal-Timestamp</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="X-Internal-Timestamp"                data-endpoint="DELETEinternal-follows"
               value="required Unix timestamp used in the HMAC signature. Example: 1735689600"
               data-component="header">
    <br>
<p>Example: <code>required Unix timestamp used in the HMAC signature. Example: 1735689600</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>X-Internal-Signature</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="X-Internal-Signature"                data-endpoint="DELETEinternal-follows"
               value="required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9"
               data-component="header">
    <br>
<p>Example: <code>required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEinternal-follows"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEinternal-follows"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>follower_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="follower_id"                data-endpoint="DELETEinternal-follows"
               value="42"
               data-component="body">
    <br>
<p>The following user's id, as used by the calling backend. Must be at least 1. Example: <code>42</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>following_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="following_id"                data-endpoint="DELETEinternal-follows"
               value="43"
               data-component="body">
    <br>
<p>The followed user's id, as used by the calling backend. The value and <code>follower_id</code> must be different. Must be at least 1. Example: <code>43</code></p>
        </div>
        </form>

                <h1 id="likes">Likes</h1>

    <p>Sync a consuming backend's likes (on posts or comments) into the feed microservice's own copy.</p>

                                <h2 id="likes-PUTinternal-likes--id-">Sync a like</h2>

<p>
</p>

<p>Idempotent upsert. likeable_type must be "post" or "comment", and the
referenced post/comment must already be synced.</p>

<span id="example-requests-PUTinternal-likes--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost/internal/likes/3001" \
    --header "X-Internal-Timestamp: required Unix timestamp used in the HMAC signature. Example: 1735689600" \
    --header "X-Internal-Signature: required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"user_id\": 42,
    \"likeable_id\": 1001,
    \"likeable_type\": \"post\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/internal/likes/3001"
);

const headers = {
    "X-Internal-Timestamp": "required Unix timestamp used in the HMAC signature. Example: 1735689600",
    "X-Internal-Signature": "required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "user_id": 42,
    "likeable_id": 1001,
    "likeable_type": "post"
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTinternal-likes--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 200,
    &quot;message&quot;: &quot;Like synced successfully.&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 422,
    &quot;message&quot;: &quot;The given data was invalid.&quot;,
    &quot;error&quot;: {
        &quot;likeable_type&quot;: [
            &quot;The selected likeable type is invalid.&quot;
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-PUTinternal-likes--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTinternal-likes--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTinternal-likes--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTinternal-likes--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTinternal-likes--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTinternal-likes--id-" data-method="PUT"
      data-path="internal/likes/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTinternal-likes--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTinternal-likes--id-"
                    onclick="tryItOut('PUTinternal-likes--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTinternal-likes--id-"
                    onclick="cancelTryOut('PUTinternal-likes--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTinternal-likes--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>internal/likes/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>X-Internal-Timestamp</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="X-Internal-Timestamp"                data-endpoint="PUTinternal-likes--id-"
               value="required Unix timestamp used in the HMAC signature. Example: 1735689600"
               data-component="header">
    <br>
<p>Example: <code>required Unix timestamp used in the HMAC signature. Example: 1735689600</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>X-Internal-Signature</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="X-Internal-Signature"                data-endpoint="PUTinternal-likes--id-"
               value="required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9"
               data-component="header">
    <br>
<p>Example: <code>required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTinternal-likes--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTinternal-likes--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="PUTinternal-likes--id-"
               value="3001"
               data-component="url">
    <br>
<p>The like's id, as used by the calling backend. Example: <code>3001</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>user_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="user_id"                data-endpoint="PUTinternal-likes--id-"
               value="42"
               data-component="body">
    <br>
<p>The liking user's id, as used by the calling backend. Must be at least 1. Example: <code>42</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>likeable_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="likeable_id"                data-endpoint="PUTinternal-likes--id-"
               value="1001"
               data-component="body">
    <br>
<p>The id of the post or comment being liked. Must already be synced. Must be at least 1. Example: <code>1001</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>likeable_type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="likeable_type"                data-endpoint="PUTinternal-likes--id-"
               value="post"
               data-component="body">
    <br>
<p>Either "post" or "comment". Example: <code>post</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>post</code></li> <li><code>comment</code></li></ul>
        </div>
        </form>

                    <h2 id="likes-DELETEinternal-likes--id-">Delete a like</h2>

<p>
</p>

<p>Idempotent: returns 200 whether or not the like existed locally.</p>

<span id="example-requests-DELETEinternal-likes--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost/internal/likes/3001" \
    --header "X-Internal-Timestamp: required Unix timestamp used in the HMAC signature. Example: 1735689600" \
    --header "X-Internal-Signature: required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/internal/likes/3001"
);

const headers = {
    "X-Internal-Timestamp": "required Unix timestamp used in the HMAC signature. Example: 1735689600",
    "X-Internal-Signature": "required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEinternal-likes--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 200,
    &quot;message&quot;: &quot;Like deleted successfully.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-DELETEinternal-likes--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEinternal-likes--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEinternal-likes--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEinternal-likes--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEinternal-likes--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEinternal-likes--id-" data-method="DELETE"
      data-path="internal/likes/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEinternal-likes--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEinternal-likes--id-"
                    onclick="tryItOut('DELETEinternal-likes--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEinternal-likes--id-"
                    onclick="cancelTryOut('DELETEinternal-likes--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEinternal-likes--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>internal/likes/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>X-Internal-Timestamp</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="X-Internal-Timestamp"                data-endpoint="DELETEinternal-likes--id-"
               value="required Unix timestamp used in the HMAC signature. Example: 1735689600"
               data-component="header">
    <br>
<p>Example: <code>required Unix timestamp used in the HMAC signature. Example: 1735689600</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>X-Internal-Signature</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="X-Internal-Signature"                data-endpoint="DELETEinternal-likes--id-"
               value="required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9"
               data-component="header">
    <br>
<p>Example: <code>required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEinternal-likes--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEinternal-likes--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="DELETEinternal-likes--id-"
               value="3001"
               data-component="url">
    <br>
<p>The like's id, as used by the calling backend. Example: <code>3001</code></p>
            </div>
                    </form>

                <h1 id="posts">Posts</h1>

    <p>Sync a consuming backend's posts into the feed microservice's own copy.</p>

                                <h2 id="posts-PUTinternal-posts--id-">Sync a post</h2>

<p>
</p>

<p>Idempotent upsert: creates the post if it doesn't exist locally yet, or
updates it if it does. The {id} must be the same id the calling backend
uses for this post — it becomes this service's primary key too.</p>

<span id="example-requests-PUTinternal-posts--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost/internal/posts/1001" \
    --header "X-Internal-Timestamp: required Unix timestamp used in the HMAC signature. Example: 1735689600" \
    --header "X-Internal-Signature: required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"author_id\": 42,
    \"content\": \"Hello world\",
    \"type\": \"text\",
    \"status\": \"published\",
    \"likes_count\": 3,
    \"comments_count\": 1,
    \"reposts_count\": 0,
    \"posted_at\": \"2026-08-06T12:00:00Z\",
    \"hashtags\": [
        \"laravel\"
    ],
    \"media\": [
        {
            \"url\": \"https:\\/\\/example.com\\/media\\/1.jpg\",
            \"position\": 0
        }
    ]
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/internal/posts/1001"
);

const headers = {
    "X-Internal-Timestamp": "required Unix timestamp used in the HMAC signature. Example: 1735689600",
    "X-Internal-Signature": "required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "author_id": 42,
    "content": "Hello world",
    "type": "text",
    "status": "published",
    "likes_count": 3,
    "comments_count": 1,
    "reposts_count": 0,
    "posted_at": "2026-08-06T12:00:00Z",
    "hashtags": [
        "laravel"
    ],
    "media": [
        {
            "url": "https:\/\/example.com\/media\/1.jpg",
            "position": 0
        }
    ]
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTinternal-posts--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 200,
    &quot;message&quot;: &quot;Post synced successfully.&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 422,
    &quot;message&quot;: &quot;The given data was invalid.&quot;,
    &quot;error&quot;: {
        &quot;author_id&quot;: [
            &quot;The author id field is required.&quot;
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-PUTinternal-posts--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTinternal-posts--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTinternal-posts--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTinternal-posts--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTinternal-posts--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTinternal-posts--id-" data-method="PUT"
      data-path="internal/posts/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTinternal-posts--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTinternal-posts--id-"
                    onclick="tryItOut('PUTinternal-posts--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTinternal-posts--id-"
                    onclick="cancelTryOut('PUTinternal-posts--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTinternal-posts--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>internal/posts/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>X-Internal-Timestamp</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="X-Internal-Timestamp"                data-endpoint="PUTinternal-posts--id-"
               value="required Unix timestamp used in the HMAC signature. Example: 1735689600"
               data-component="header">
    <br>
<p>Example: <code>required Unix timestamp used in the HMAC signature. Example: 1735689600</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>X-Internal-Signature</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="X-Internal-Signature"                data-endpoint="PUTinternal-posts--id-"
               value="required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9"
               data-component="header">
    <br>
<p>Example: <code>required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTinternal-posts--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTinternal-posts--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="PUTinternal-posts--id-"
               value="1001"
               data-component="url">
    <br>
<p>The post's id, as used by the calling backend. Example: <code>1001</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>author_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="author_id"                data-endpoint="PUTinternal-posts--id-"
               value="42"
               data-component="body">
    <br>
<p>The post author's id, as used by the calling backend. Must be at least 1. Example: <code>42</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>content</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="content"                data-endpoint="PUTinternal-posts--id-"
               value="Hello world"
               data-component="body">
    <br>
<p>The post text. Example: <code>Hello world</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="type"                data-endpoint="PUTinternal-posts--id-"
               value="text"
               data-component="body">
    <br>
<p>One of: text, image, video, multi_image. Example: <code>text</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>text</code></li> <li><code>image</code></li> <li><code>video</code></li> <li><code>multi_image</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="PUTinternal-posts--id-"
               value="published"
               data-component="body">
    <br>
<p>One of: published, draft, hidden. Only published posts appear in feeds. Example: <code>published</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>published</code></li> <li><code>draft</code></li> <li><code>hidden</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>likes_count</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="likes_count"                data-endpoint="PUTinternal-posts--id-"
               value="3"
               data-component="body">
    <br>
<p>Current like count, as tracked by the calling backend. Must be at least 0. Example: <code>3</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>comments_count</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="comments_count"                data-endpoint="PUTinternal-posts--id-"
               value="1"
               data-component="body">
    <br>
<p>Current comment count, as tracked by the calling backend. Must be at least 0. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>reposts_count</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="reposts_count"                data-endpoint="PUTinternal-posts--id-"
               value="0"
               data-component="body">
    <br>
<p>Current repost count, as tracked by the calling backend. Must be at least 0. Example: <code>0</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>reposted_from_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="reposted_from_id"                data-endpoint="PUTinternal-posts--id-"
               value=""
               data-component="body">
    <br>
<p>If this is a repost, the id of the original post (must already be synced). Must be at least 1.</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>posted_at</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="posted_at"                data-endpoint="PUTinternal-posts--id-"
               value="2026-08-06T12:00:00Z"
               data-component="body">
    <br>
<p>The post's original creation time in the calling backend — used for feed ordering. Must be a valid date. Example: <code>2026-08-06T12:00:00Z</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>media</code></b>&nbsp;&nbsp;
<small>object[]</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
<br>
<p>Attached media, replacing whatever was previously stored for this post.</p>
            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>url</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="media.0.url"                data-endpoint="PUTinternal-posts--id-"
               value="https://example.com/media/1.jpg"
               data-component="body">
    <br>
<p>A publicly resolvable media URL. Must be a valid URL. Example: <code>https://example.com/media/1.jpg</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>position</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="media.0.position"                data-endpoint="PUTinternal-posts--id-"
               value="0"
               data-component="body">
    <br>
<p>Display order within the post, 0-indexed. Must be at least 0. Example: <code>0</code></p>
                    </div>
                                    </details>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>hashtags</code></b>&nbsp;&nbsp;
<small>string[]</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="hashtags[0]"                data-endpoint="PUTinternal-posts--id-"
               data-component="body">
        <input type="text" style="display: none"
               name="hashtags[1]"                data-endpoint="PUTinternal-posts--id-"
               data-component="body">
    <br>
<p>A hashtag name, without the leading #. Must not be greater than 255 characters.</p>
        </div>
        </form>

                    <h2 id="posts-DELETEinternal-posts--id-">Delete a post</h2>

<p>
</p>

<p>Idempotent: returns 200 whether or not the post existed locally.</p>

<span id="example-requests-DELETEinternal-posts--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost/internal/posts/1001" \
    --header "X-Internal-Timestamp: required Unix timestamp used in the HMAC signature. Example: 1735689600" \
    --header "X-Internal-Signature: required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/internal/posts/1001"
);

const headers = {
    "X-Internal-Timestamp": "required Unix timestamp used in the HMAC signature. Example: 1735689600",
    "X-Internal-Signature": "required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEinternal-posts--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 200,
    &quot;message&quot;: &quot;Post deleted successfully.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-DELETEinternal-posts--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEinternal-posts--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEinternal-posts--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEinternal-posts--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEinternal-posts--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEinternal-posts--id-" data-method="DELETE"
      data-path="internal/posts/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEinternal-posts--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEinternal-posts--id-"
                    onclick="tryItOut('DELETEinternal-posts--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEinternal-posts--id-"
                    onclick="cancelTryOut('DELETEinternal-posts--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEinternal-posts--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>internal/posts/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>X-Internal-Timestamp</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="X-Internal-Timestamp"                data-endpoint="DELETEinternal-posts--id-"
               value="required Unix timestamp used in the HMAC signature. Example: 1735689600"
               data-component="header">
    <br>
<p>Example: <code>required Unix timestamp used in the HMAC signature. Example: 1735689600</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>X-Internal-Signature</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="X-Internal-Signature"                data-endpoint="DELETEinternal-posts--id-"
               value="required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9"
               data-component="header">
    <br>
<p>Example: <code>required HMAC-SHA256 signature — see the Authentication section. Example: 5e1a3f...c9</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEinternal-posts--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEinternal-posts--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="DELETEinternal-posts--id-"
               value="1001"
               data-component="url">
    <br>
<p>The post's id, as used by the calling backend. Example: <code>1001</code></p>
            </div>
                    </form>

            

        
    </div>
    <div class="dark-box">
                    <div class="lang-selector">
                                                        <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                                        <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                            </div>
            </div>
</div>
</body>
</html>
