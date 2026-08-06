<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

/**
 * Lets any consuming backend control the language of API responses
 * (validation errors, exception messages, ...) via the standard
 * Accept-Language request header.
 */
final class SetLocaleFromRequest
{
    private const array SUPPORTED_LOCALES = ['en', 'ar'];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->getPreferredLanguage(self::SUPPORTED_LOCALES);

        if (is_string($locale)) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
