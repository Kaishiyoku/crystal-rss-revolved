<?php

use App\Models\Feed;
use App\Services\TypeScriptModelGenerator\Nodes\InheritedType;
use Illuminate\Validation\ValidationException;
use Kaishiyoku\HeraRssCrawler\Models\Rss\FeedItem as RssFeedItem;

it('builds an inherited type', function () {
    expect(InheritedType::fromConfig([
        'name' => 'FeedWithFeedItemsCount',
        'model' => Feed::class,
        'additional_fields' => [
            [
                'name' => 'feed_items_count',
                'types' => ['number', 'null'],
            ],
        ],
    ])->toString())->toMatchSnapshot();
});

it('fails to validate the config', function (array $config, string $message, string $exception = ValidationException::class) {
    expect(fn () => InheritedType::fromConfig($config))
        ->toThrow($exception, $message);
})->with([
    'name is empty' => [
        [
            'name' => '',
            'model' => Feed::class,
            'additional_fields' => [
                [
                    'name' => 'feed_items_count',
                    'types' => ['number', 'null'],
                ],
            ],
        ],
        'The Name field is required.',
    ],
    'name is not a string' => [
        [
            'name' => 1,
            'model' => Feed::class,
            'additional_fields' => [
                [
                    'name' => 'feed_items_count',
                    'types' => ['number', 'null'],
                ],
            ],
        ],
        'The Name must be a string.',
    ],
    'model is empty' => [
        [
            'name' => 'FeedWithFeedItemsCount',
            'model' => '',
            'additional_fields' => [
                [
                    'name' => 'feed_items_count',
                    'types' => ['number', 'null'],
                ],
            ],
        ],
        'The model field is required.',
    ],
    'model is not a string' => [
        [
            'name' => 'FeedWithFeedItemsCount',
            'model' => 1,
            'additional_fields' => [
                [
                    'name' => 'feed_items_count',
                    'types' => ['number', 'null'],
                ],
            ],
        ],
        'The model must be a string.',
    ],
    'model is not instantiable' => [
        [
            'name' => 'FeedWithFeedItemsCount',
            'model' => 'NonExistent',
            'additional_fields' => [
                [
                    'name' => 'feed_items_count',
                    'types' => ['number', 'null'],
                ],
            ],
        ],
        'config field [model] is not a valid model (class not found)',
        InvalidArgumentException::class,
    ],
    'model is not a model class' => [
        [
            'name' => 'FeedWithFeedItemsCount',
            'model' => RssFeedItem::class,
            'additional_fields' => [
                [
                    'name' => 'feed_items_count',
                    'types' => ['number', 'null'],
                ],
            ],
        ],
        'config field [model] is not a valid model (not a model class)',
        InvalidArgumentException::class,
    ],
    'additional_fields is not an array' => [
        [
            'name' => 'FeedWithFeedItemsCount',
            'model' => Feed::class,
            'additional_fields' => 1,
        ],
        'The additional fields must be an array.',
    ],
    'additional_fields is empty' => [
        [
            'name' => 'FeedWithFeedItemsCount',
            'model' => Feed::class,
            'additional_fields' => [],
        ],
        'The additional fields field is required.',
    ],
    'additional_fields.*.name is not a string' => [
        [
            'name' => 'FeedWithFeedItemsCount',
            'model' => Feed::class,
            'additional_fields' => [
                [
                    'name' => 'feed_items_count',
                    'types' => ['number', 'null'],
                ],
                [
                    'name' => 1,
                    'types' => ['number'],
                ],
            ],
        ],
        'The additional_fields.1.name must be a string.',
    ],
    'additional_fields.*.name is empty' => [
        [
            'name' => 'FeedWithFeedItemsCount',
            'model' => Feed::class,
            'additional_fields' => [
                [
                    'name' => 'feed_items_count',
                    'types' => ['number', 'null'],
                ],
                [
                    'name' => '',
                    'types' => ['number'],
                ],
            ],
        ],
        'The additional_fields.1.name field is required',
    ],
    'additional_fields.*.types is not an array' => [
        [
            'name' => 'FeedWithFeedItemsCount',
            'model' => Feed::class,
            'additional_fields' => [
                [
                    'name' => 'feed_items_count',
                    'types' => ['number', 'null'],
                ],
                [
                    'name' => 'test',
                    'types' => 1,
                ],
            ],
        ],
        'The additional_fields.1.types must be an array.',
    ],
    'additional_fields.*.types is empty' => [
        [
            'name' => 'FeedWithFeedItemsCount',
            'model' => Feed::class,
            'additional_fields' => [
                [
                    'name' => 'feed_items_count',
                    'types' => ['number', 'null'],
                ],
                [
                    'name' => 'test',
                    'types' => [],
                ],
            ],
        ],
        'The additional_fields.1.types field is required.',
    ],
    'additional_fields.*.types.* is not a valid enum value' => [
        [
            'name' => 'FeedWithFeedItemsCount',
            'model' => Feed::class,
            'additional_fields' => [
                [
                    'name' => 'feed_items_count',
                    'types' => ['number', 'null'],
                ],
                [
                    'name' => 'test',
                    'types' => ['test'],
                ],
            ],
        ],
        'The selected additional_fields.1.types is invalid.',
    ],
    'name is the same as model name' => [
        [
            'name' => 'Feed',
            'model' => Feed::class,
            'additional_fields' => [
                [
                    'name' => 'feed_items_count',
                    'types' => ['number', 'null'],
                ],
            ],
        ],
        'The selected Name is invalid.',
    ],
]);
