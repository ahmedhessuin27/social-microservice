<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::enforceMorphMap([
            'post' => Post::class,
            'comment' => Comment::class,
        ]);

        RateLimiter::for(
            'internal-api',
            fn (Request $request): Limit => Limit::perMinute(config('internal-api.rate_limit_per_minute'))
                ->by($request->ip()),
        );
    }
}
