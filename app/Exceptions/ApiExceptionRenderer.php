<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

/**
 * Maps every exception this API can throw to the Response::api() envelope.
 * Registered from bootstrap/app.php's withExceptions() — kept as its own
 * class purely to keep that file scannable, not because Laravel needs it
 * split out (the render() callbacks below are the actual Laravel 13 API).
 *
 * Note: Laravel's Handler::prepareException() converts ModelNotFoundException
 * into NotFoundHttpException, and a status-less AuthorizationException into
 * AccessDeniedHttpException, *before* any render() callback runs — so those
 * are the types to catch here, not the originals.
 */
final class ApiExceptionRenderer
{
    public static function register(Exceptions $exceptions): void
    {
        $exceptions->render(fn (AuthenticationException $e): JsonResponse => response()->api(
            __('errors.unauthenticated'),
            401,
        ));

        $exceptions->render(fn (AccessDeniedHttpException $e): JsonResponse => response()->api(
            __('errors.forbidden'),
            403,
        ));

        $exceptions->render(fn (AuthorizationException $e): JsonResponse => response()->api(
            __('errors.forbidden'),
            403,
        ));

        $exceptions->render(fn (NotFoundHttpException $e): JsonResponse => response()->api(
            __('errors.not_found'),
            404,
        ));

        $exceptions->render(fn (ValidationException $e): JsonResponse => response()->api(
            __('errors.validation_failed'),
            422,
            null,
            $e->errors(),
        ));

        $exceptions->render(fn (ThrottleRequestsException $e): JsonResponse => response()->api(
            __('errors.too_many_requests'),
            429,
        ));

        $exceptions->render(function (Throwable $e): ?JsonResponse {
            if (config('app.debug')) {
                return null;
            }

            return response()->api(__('errors.server_error'), 500);
        });
    }
}
