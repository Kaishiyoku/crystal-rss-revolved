<?php

use App\Models\Feed;
use App\Services\TypeScriptModelGenerator\Nodes\ModelPartial;
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

it('throws an exception if the config has an invalid format', function () {
    ModelPartial::fromConfig([]);
})->throws(InvalidArgumentException::class, 'invalid config array');

it('throws an exception if the name config field is not a string', function () {
    ModelPartial::fromConfig([
        'name' => 1,
        'model' => Feed::class,
        'fields' => [
            'id',
            'name',
        ],
    ]);
})->throws(InvalidArgumentException::class, 'invalid config value [1] for the field [name]');

it('throws an exception if the name config field is an empty string', function () {
    ModelPartial::fromConfig([
        'name' => '',
        'model' => Feed::class,
        'fields' => [
            'id',
            'name',
        ],
    ]);
})->throws(InvalidArgumentException::class, 'config field [name] may not be empty');

it('throws an exception if the model config field is not a string', function () {
    ModelPartial::fromConfig([
        'name' => 'ShortFeed',
        'model' => 1,
        'fields' => [
            'id',
            'name',
        ],
    ]);
})->throws(InvalidArgumentException::class, 'invalid config value [1] for the field [model]');

it('throws an exception if the model config field is an empty string', function () {
    ModelPartial::fromConfig([
        'name' => 'ShortFeed',
        'model' => '',
        'fields' => [
            'id',
            'name',
        ],
    ]);
})->throws(InvalidArgumentException::class, 'config field [model] may not be empty');

it('throws an exception if the model config field is a nonexistent class', function () {
    ModelPartial::fromConfig([
        'name' => 'ShortFeed',
        'model' => '\\App\\Models\\NonExistentModel',
        'fields' => [
            'id',
            'name',
        ],
    ]);

})->throws(InvalidArgumentException::class, 'config field [model] is not a valid model (class not found)');

it('throws an exception if the model config field is not a model class', function () {
    ModelPartial::fromConfig([
        'name' => 'ShortFeed',
        'model' => RssFeedItem::class,
        'fields' => [
            'id',
            'name',
        ],
    ]);
})->throws(InvalidArgumentException::class, 'config field [model] is not a valid model (not a model class)');

it('throws an exception if the fields config field is not an array', function () {
    ModelPartial::fromConfig([
        'name' => 'ShortFeed',
        'model' => Feed::class,
        'fields' => 1,
    ]);
})->throws(InvalidArgumentException::class, 'invalid config value [1] for the field [fields]');

it('throws an exception if the fields config field is an empty array', function () {
    ModelPartial::fromConfig([
        'name' => 'ShortFeed',
        'model' => Feed::class,
        'fields' => [],
    ]);
})->throws(InvalidArgumentException::class, 'config field [fields] may not be empty');
