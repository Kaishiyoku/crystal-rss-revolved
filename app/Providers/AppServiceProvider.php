<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Kaishiyoku\HeraRssCrawler\HeraRssCrawler;

class AppServiceProvider extends ServiceProvider
{
    /** The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(HeraRssCrawler::class, function (Application $app) {
            $heraRssCrawler = new HeraRssCrawler;
            $heraRssCrawler->setLogger(Log::channel('feed_updater'));
            $heraRssCrawler->setRetryCount(config('app.rss_crawler_retry_count'));

            return $heraRssCrawler;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //

        Gate::define('viewPulse', function (User $user) {
            return $user->is_admin;
        });

        $this->bootRoute();
    }

    public function bootRoute(): void
    {
        // @codeCoverageIgnoreStart
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
        // @codeCoverageIgnoreEnd
    }
}
