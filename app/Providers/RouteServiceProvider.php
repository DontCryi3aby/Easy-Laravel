<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });

        // Custom RateLimit by Thach
        RateLimiter::for('customLimitWithMethod', function (Request $request) {
            if ($request->isMethod('GET')) {
                return Limit::perSeconds(1, 60)->response(function (Request $request) {
                    return response()->json([
                        'message' => 'Too many requests for GET api...',
                    ],429);
                });
            }

            return Limit::perSeconds(1,10)->response(function (Request $request) {
                return response()->json([
                    'message' => 'Too many requests for this api...',
                ],429);
            });
        });
    }
}