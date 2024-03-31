<?php

use App\Models\Feed;
use App\Services\TypeScriptModelGenerator\Nodes\InheritedTypePartial;
use Illuminate\Validation\ValidationException;

beforeEach(function () {
    Config::set('type-script-model-generator.inherited_types', [
        [
            'name' => 'FeedWithFeedItemsCount',
            'model' => Feed::class,
            'additional_fields' => [
                [
                    'name' => 'feed_items_count',
                    'types' => ['number'],
                ],
            ],
        ],
    ]);
});

it('builds an inherited partial type', function () {
    expect(InheritedTypePartial::fromConfig([
        'name' => 'ShortFeedWithFeedItemsCount',
        'type' => 'FeedWithFeedItemsCount',
        'fields' => ['id', 'feed_items_count'],
    ])->toString())->toMatchSnapshot();
});

it('fails to validate the config', function (array $config, string $message, string $exception = ValidationException::class) {
    expect(fn () => InheritedTypePartial::fromConfig($config))
        ->toThrow($exception, $message);
})->with([
    'name is not a string' => [
        [
            'name' => 1,
            'type' => 'FeedWithFeedItemsCount',
            'fields' => ['id', 'feed_items_count'],
        ],
        'The Name field must be a string.',
    ],
    'name is empty' => [
        [
            'name' => '',
            'type' => 'FeedWithFeedItemsCount',
            'fields' => ['id', 'feed_items_count'],
        ],
        'The Name field is required.',
    ],
    'type is not a string' => [
        [
            'name' => 'ShortFeedWithFeedItemsCount',
            'type' => 1,
            'fields' => ['id', 'feed_items_count'],
        ],
        'The type field must be a string.',
    ],
    'type is empty' => [
        [
            'name' => 'ShortFeedWithFeedItemsCount',
            'type' => '',
            'fields' => ['id', 'feed_items_count'],
        ],
        'The type field is required.',
    ],
    'type is not a configured inherited type' => [
        [
            'name' => 'ShortFeedWithFeedItemsCount',
            'type' => 'NonExistent',
            'fields' => ['id', 'feed_items_count'],
        ],
        'The selected type is invalid.',
    ],
    'fields is not an array' => [
        [
            'name' => 'ShortFeedWithFeedItemsCount',
            'type' => 'FeedWithFeedItemsCount',
            'fields' => 1,
        ],
        'The fields field must be an array.',
    ],
    'fields is empty' => [
        [
            'name' => 'ShortFeedWithFeedItemsCount',
            'type' => 'FeedWithFeedItemsCount',
            'fields' => [],
        ],
        'The fields field is required.',
    ],
    'fields.* is not a string' => [
        [
            'name' => 'ShortFeedWithFeedItemsCount',
            'type' => 'FeedWithFeedItemsCount',
            'fields' => ['id', 1],
        ],
        'The fields.1 field must be a string.',
    ],
    'fields.* is empty' => [
        [
            'name' => 'ShortFeedWithFeedItemsCount',
            'type' => 'FeedWithFeedItemsCount',
            'fields' => ['id', ''],
        ],
        'The fields.1 field is required.',
    ],
    'name is the same as type' => [
        [
            'name' => 'FeedWithFeedItemsCount',
            'type' => 'FeedWithFeedItemsCount',
            'fields' => ['id', 'feed_items_count'],
        ],
        'The Name field and type must be different.',
    ],
]);
