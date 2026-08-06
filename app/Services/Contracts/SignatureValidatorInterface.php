<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Exceptions\InvalidHmacSignatureException;
use App\Services\Hmac\SignatureValidator;
use Illuminate\Container\Attributes\Bind;
use Illuminate\Http\Request;

#[Bind(SignatureValidator::class)]
interface SignatureValidatorInterface
{
    /**
     * @throws InvalidHmacSignatureException
     */
    public function validate(Request $request): void;
}
