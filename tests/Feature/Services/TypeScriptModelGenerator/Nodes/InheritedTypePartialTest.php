<?php

use App\Services\TypeScriptModelGenerator\Nodes\InheritedTypePartial;
use Illuminate\Validation\ValidationException;

it('builds an inherited partial type', function () {
    expect(InheritedTypePartial::fromConfig([
        'name' => 'ShortFeedWithFeedItemsCount',
        'type' => 'ShortFeed',
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
            'type' => 'ShortFeed',
            'fields' => ['id', 'feed_items_count'],
        ],
        'The Name must be a string.',
    ],
    'name is empty' => [
        [
            'name' => '',
            'type' => 'ShortFeed',
            'fields' => ['id', 'feed_items_count'],
        ],
        'The Name field is required.',
    ],
    'type is not a string' => [
        [
            'name' => 'ShortFeed',
            'type' => 1,
            'fields' => ['id', 'feed_items_count'],
        ],
        'The type must be a string.',
    ],
    'type is empty' => [
        [
            'name' => 'ShortFeed',
            'type' => '',
            'fields' => ['id', 'feed_items_count'],
        ],
        'The type field is required.',
    ],
    'fields is not an array' => [
        [
            'name' => 'ShortFeed',
            'type' => 'ShortFeed',
            'fields' => 1,
        ],
        'The fields must be an array.',
    ],
    'fields is empty' => [
        [
            'name' => 'ShortFeed',
            'type' => 'ShortFeed',
            'fields' => [],
        ],
        'The fields field is required.',
    ],
    'fields.* is not a string' => [
        [
            'name' => 'ShortFeed',
            'type' => 'ShortFeed',
            'fields' => ['id', 1],
        ],
        'The fields.1 must be a string.',
    ],
    'fields.* is empty' => [
        [
            'name' => 'ShortFeed',
            'type' => 'ShortFeed',
            'fields' => ['id', ''],
        ],
        'The fields.1 field is required.',
    ],
]);
