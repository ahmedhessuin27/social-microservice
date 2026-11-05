<?php

declare(strict_types=1);

namespace App\Providers;

use App\Http\Resources\BaseResource;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Response::macro('api', function (
            string $message,
            int $statusCode = 200,
            $data = null,
            $errors = null
        ) {
            $response = [
                // true for any 2xx; the numeric code stays on the HTTP response itself.
                'status' => $statusCode >= 200 && $statusCode < 300,
                'message' => $message,
            ];

            if ($errors) {
                $response['error'] = $errors;

                return response()->json($response, $statusCode);
            }

            if ($data) {
                if ($data instanceof BaseResource) {
                    $resourceData = $data->toArray(request());

                    // per_page is always present (never null) for a paginated
                    // result, unlike next_cursor/prev_cursor which are absent
                    // on a single-page result with nothing to page to.
                    if (isset($resourceData['data']) && (isset($resourceData['current_page']) || isset($resourceData['per_page']))) {
                        $response = array_merge($response, $resourceData);
                    } else {
                        $response['data'] = $resourceData;
                    }
                } else {
                    $response['data'] = $data;
                }
            }

            return response()->json($response, $statusCode);
        });
    }
}
