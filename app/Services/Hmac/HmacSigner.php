<?php

declare(strict_types=1);

namespace App\Services\Hmac;

use App\Services\Contracts\HmacSignerInterface;
use Illuminate\Container\Attributes\Config;

/**
 * Reusable by both sides of the integration: this service signs outgoing
 * requests when this app is the caller, and SignatureValidator uses the
 * same sign() to verify incoming requests. Any consuming Laravel backend
 * replicates this exact canonicalization to sign its own requests to us.
 */
final readonly class HmacSigner implements HmacSignerInterface
{
    public function __construct(
        #[Config('internal-api.secret')] private ?string $secret,
        #[Config('internal-api.algorithm', 'sha256')] private string $algorithm,
    ) {}

    public function sign(string $method, string $path, string $timestamp, string $body): string
    {
        return hash_hmac(
            $this->algorithm,
            $this->canonicalize($method, $path, $timestamp, $body),
            (string) $this->secret,
        );
    }

    /**
     * METHOD\nPATH\nTIMESTAMP\nBODY — PATH has no leading slash or query
     * string, matching Illuminate\Http\Request::path().
     */
    private function canonicalize(string $method, string $path, string $timestamp, string $body): string
    {
        return implode("\n", [
            strtoupper($method),
            ltrim($path, '/'),
            $timestamp,
            $body,
        ]);
    }
}
