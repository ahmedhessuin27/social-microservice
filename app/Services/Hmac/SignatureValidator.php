<?php

declare(strict_types=1);

namespace App\Services\Hmac;

use App\Exceptions\InvalidHmacSignatureException;
use App\Services\Contracts\HmacSignerInterface;
use App\Services\Contracts\SignatureValidatorInterface;
use Illuminate\Container\Attributes\Config;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Http\Request;

final readonly class SignatureValidator implements SignatureValidatorInterface
{
    public function __construct(
        private HmacSignerInterface $signer,
        private Cache $cache,
        #[Config('internal-api.timestamp_tolerance', 300)] private int $timestampTolerance,
        #[Config('internal-api.replay_ttl', 600)] private int $replayTtl,
        #[Config('internal-api.headers.signature', 'X-Internal-Signature')] private string $signatureHeader,
        #[Config('internal-api.headers.timestamp', 'X-Internal-Timestamp')] private string $timestampHeader,
    ) {}

    public function validate(Request $request): void
    {
        $signature = $request->header($this->signatureHeader);
        $timestamp = $request->header($this->timestampHeader);

        if (! is_string($signature) || $signature === '' || ! is_string($timestamp) || $timestamp === '') {
            throw InvalidHmacSignatureException::missingHeaders();
        }

        if (! ctype_digit($timestamp) || abs(time() - (int) $timestamp) > $this->timestampTolerance) {
            throw InvalidHmacSignatureException::expiredTimestamp();
        }

        $expected = $this->signer->sign($request->method(), $request->path(), $timestamp, (string) $request->getContent());

        if (! hash_equals($expected, $signature)) {
            throw InvalidHmacSignatureException::invalidSignature();
        }

        $replayKey = 'hmac-replay:'.hash('sha256', $signature);

        if ($this->cache->has($replayKey)) {
            throw InvalidHmacSignatureException::replayDetected();
        }

        $this->cache->put($replayKey, true, $this->replayTtl);
    }
}
