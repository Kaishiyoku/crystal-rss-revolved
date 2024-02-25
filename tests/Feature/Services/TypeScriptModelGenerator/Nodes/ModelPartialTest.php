<?php

use App\Models\Feed;
use App\Services\TypeScriptModelGenerator\Nodes\ModelPartial;
use Illuminate\Validation\ValidationException;
use Kaishiyoku\HeraRssCrawler\Models\Rss\FeedItem as RssFeedItem;

it('builds a model partial type', function () {
    expect(ModelPartial::fromConfig([
        'name' => 'ShortFeed',
        'model' => Feed::class,
        'fields' => [
            'id',
            'name',
        ],
    ])->toString())->toMatchSnapshot();
});

it('fails to validate the config', function (array $config, string $message, string $exception = ValidationException::class) {
    expect(fn () => ModelPartial::fromConfig($config))
        ->toThrow($exception, $message);
})->with([
    'name is not a string' => [
        [
            'name' => 1,
            'model' => Feed::class,
            'fields' => ['id', 'name'],
        ],
        'The Name must be a string.',
    ],
    'name is empty' => [
        [
            'name' => '',
            'model' => Feed::class,
            'fields' => ['id', 'name'],
        ],
        'The Name field is required.',
    ],
    'model is not a string' => [
        [
            'name' => 'ShortFeed',
            'model' => 1,
            'fields' => ['id', 'name'],
        ],
        'The model must be a string.',
    ],
    'model is empty' => [
        [
            'name' => 'ShortFeed',
            'model' => '',
            'fields' => ['id', 'name'],
        ],
        'The model field is required.',
    ],
    'model is not instantiable' => [
        [
            'name' => 'ShortFeed',
            'model' => 'NonExistent',
            'fields' => ['id', 'name'],
        ],
        'config field [model] is not a valid model (class not found)',
        InvalidArgumentException::class,
    ],
    'model is not a model class' => [
        [
            'name' => 'ShortFeed',
            'model' => RssFeedItem::class,
            'fields' => ['id', 'name'],
        ],
        'config field [model] is not a valid model (not a model class)',
        InvalidArgumentException::class
    ],
    'fields is not an array' => [
        [
            'name' => 'ShortFeed',
            'model' => Feed::class,
            'fields' => 1,
        ],
        'The fields must be an array.',
    ],
    'fields is empty' => [
        [
            'name' => 'ShortFeed',
            'model' => Feed::class,
            'fields' => [],
        ],
        'The fields field is required.',
    ],
    'fields.* is not a string' => [
        [
            'name' => 'ShortFeed',
            'model' => Feed::class,
            'fields' => ['id', 1],
        ],
        'The fields.1 must be a string.',
    ],
    'fields.* is empty' => [
        [
            'name' => 'ShortFeed',
            'model' => Feed::class,
            'fields' => ['id', ''],
        ],
        'The fields.1 field is required.',
    ],
]);
