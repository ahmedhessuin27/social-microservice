<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Shared HMAC Secret
    |--------------------------------------------------------------------------
    |
    | The secret used to sign and verify requests between this microservice
    | and any consuming Laravel backend. Both sides must be configured with
    | the exact same value, distributed out-of-band (deployment secrets).
    |
    */

    'secret' => env('INTERNAL_API_SECRET'),

    'algorithm' => env('INTERNAL_API_HMAC_ALGORITHM', 'sha256'),

    /*
    |--------------------------------------------------------------------------
    | Timestamp Tolerance & Replay Window
    |--------------------------------------------------------------------------
    |
    | timestamp_tolerance: maximum allowed clock drift, in seconds, between
    | the request's timestamp header and this server's time.
    |
    | replay_ttl: how long a seen signature is remembered in the cache to
    | block a byte-identical request from being replayed.
    |
    */

    'timestamp_tolerance' => (int) env('INTERNAL_API_TIMESTAMP_TOLERANCE', 300),

    'replay_ttl' => (int) env('INTERNAL_API_REPLAY_TTL', 600),

    /*
    |--------------------------------------------------------------------------
    | Header Names
    |--------------------------------------------------------------------------
    */

    'headers' => [
        'signature' => env('INTERNAL_API_SIGNATURE_HEADER', 'X-Internal-Signature'),
        'timestamp' => env('INTERNAL_API_TIMESTAMP_HEADER', 'X-Internal-Timestamp'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Maximum requests per minute, per client IP, across all internal/*
    | routes. Keyed by IP rather than globally so one noisy consumer can't
    | exhaust the budget for every other backend integrated with this service.
    |
    */

    'rate_limit_per_minute' => (int) env('INTERNAL_API_RATE_LIMIT', 120),

];
