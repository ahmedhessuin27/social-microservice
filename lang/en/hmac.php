<?php

declare(strict_types=1);

return [
    'missing_headers' => 'Missing HMAC signature or timestamp header.',
    'expired_timestamp' => 'Request timestamp is outside the allowed tolerance window.',
    'invalid_signature' => 'HMAC signature does not match.',
    'replay_detected' => 'This request has already been processed.',
];
