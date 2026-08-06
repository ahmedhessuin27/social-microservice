<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Self-rendering: Laravel's exception handler calls render() on any
 * exception that defines it, so throwing this from anywhere in the
 * HMAC flow is enough — no special-casing needed in a handler.
 */
final class InvalidHmacSignatureException extends Exception
{
    public static function missingHeaders(): self
    {
        return new self(__('hmac.missing_headers'));
    }

    public static function expiredTimestamp(): self
    {
        return new self(__('hmac.expired_timestamp'));
    }

    public static function invalidSignature(): self
    {
        return new self(__('hmac.invalid_signature'));
    }

    public static function replayDetected(): self
    {
        return new self(__('hmac.replay_detected'));
    }

    public function render(Request $request): JsonResponse
    {
        return response()->api($this->getMessage(), 401);
    }
}
