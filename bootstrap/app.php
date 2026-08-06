<?php

use App\Exceptions\ApiExceptionRenderer;
use App\Http\Middleware\SetLocaleFromRequest;
use App\Http\Middleware\VerifyHmacSignature;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function (): void {
            Route::prefix('internal')
                ->middleware(['throttle:internal-api', 'hmac'])
                ->group(base_path('routes/internal.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(SetLocaleFromRequest::class);
        $middleware->alias(['hmac' => VerifyHmacSignature::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // This microservice is API-only (no web/session UI, routes live
        // under internal/*, not api/*) — every error response is JSON,
        // unconditionally, regardless of the request's Accept header.
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request): bool => true,
        );

        ApiExceptionRenderer::register($exceptions);
    })->create();
