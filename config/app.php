<?php

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Facade;

return [

    'available_locales' => ['en', 'de'],

    'rss_crawler_retry_count' => env('RSS_CRAWLER_RETRY_COUNT', 2),

    'feed_items_per_page' => env('FEED_ITEMS_PER_PAGE', 15),

    'months_after_pruning_feed_items' => env('MONTHS_AFTER_PRUNING_FEED_ITEMS', 2),

    'fetch_articles_not_older_than_months' => env('FETCH_ARTICLES_NOT_OLDER_THAN_MONTHS', 2),

    'contact_email' => env('CONTACT_EMAIL'),

    'github_url' => env('GITHUB_URL'),

    'providers' => ServiceProvider::defaultProviders()->merge([
        /*
         * Laravel Framework Service Providers...
         */

        /*
         * Package Service Providers...
         */

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        App\Providers\TelescopeServiceProvider::class,
        App\Providers\HorizonServiceProvider::class,
    ])->toArray(),

];
