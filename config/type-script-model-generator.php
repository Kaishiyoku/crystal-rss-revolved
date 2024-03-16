<?php

return [

    'output_directory' => env('TYPE_SCRIPT_GENERATOR_OUTPUT_DIR', './resources/js/types/generated/Models'),
    'model_directory' => env('TYPE_SCRIPT_GENERATOR_MODEL_DIR', './app/Models'),
    'import_directory' => env('TYPE_SCRIPT_GENERATOR_IMPORT_DIR', '@/types/generated/Models'),

    // model types
    'model_partials' => [
        [
            'name' => 'ShortFeed',
            'model' => \App\Models\Feed::class,
            'fields' => [
                'id',
                'name',
            ],
        ],
    ],

    // extended model types with additional fields
    'inherited_types' => [
        [
            'name' => 'CategoryWithFeedsCount',
            'model' => \App\Models\Category::class,
            'additional_fields' => [
                [
                    'name' => 'feeds_count',
                    'types' => ['number'],
                ],
            ],
        ],
        [
            'name' => 'FeedWithFeedItemsCount',
            'model' => \App\Models\Feed::class,
            'additional_fields' => [
                [
                    'name' => 'feed_items_count',
                    'types' => ['number'],
                ],
            ],
        ],
        [
            'name' => 'UserWithFeedsCountAndUnreadFeedItemsCount',
            'model' => \App\Models\User::class,
            'additional_fields' => [
                [
                    'name' => 'feeds_count',
                    'types' => ['number'],
                ],
                [
                    'name' => 'unread_feed_items_count',
                    'types' => ['number'],
                ],
            ],
        ],
    ],

    // partials by using already generated inherited types
    'inherited_type_partials' => [
        [
            'name' => 'ShortFeedWithFeedItemsCount',
            'type' => 'FeedWithFeedItemsCount',
            'fields' => [
                'id',
                'name',
                'feed_items_count',
            ],
        ],
    ],

];
