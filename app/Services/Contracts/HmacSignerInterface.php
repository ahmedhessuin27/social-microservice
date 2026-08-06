<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Services\Hmac\HmacSigner;
use Illuminate\Container\Attributes\Bind;

#[Bind(HmacSigner::class)]
interface HmacSignerInterface
{
    /**
     * Compute the HMAC signature for a request, to be sent as the
     * signature header alongside the same timestamp used here.
     */
    public function sign(string $method, string $path, string $timestamp, string $body): string;
}
