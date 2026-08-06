<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Services\Contracts\SignatureValidatorInterface;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class VerifyHmacSignature
{
    public function __construct(
        private readonly SignatureValidatorInterface $validator,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $this->validator->validate($request);

        return $next($request);
    }
}
