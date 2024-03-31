<?php

return [

    'channels' => [
        'feed_updater' => [
            'driver' => 'daily',
            'path' => storage_path('logs/feed_updater.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 14,
        ],
    ],

];
