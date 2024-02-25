<?php

use App\Models\Feed;
use App\Models\FeedItem;
use App\Services\TypeScriptModelGenerator\Nodes\Type;
use App\Services\TypeScriptModelGenerator\Nodes\TypeProperty;

it('builds a type property', function () {
    expect((new TypeProperty(new FeedItem(), 'checksum'))->toString())->toBe('checksum: string;')
        ->and((new TypeProperty(new FeedItem(), 'posted_at'))->toString())->toBe('posted_at: string /** cast attribute */;')
        ->and((new TypeProperty(new FeedItem(), 'has_image'))->toString())->toBe('has_image: boolean /** model attribute */;')
        ->and((new TypeProperty(new FeedItem(), 'non_existent_field'))->toString())->toBe('non_existent_field: unknown /** no return types found */;');
});

it('builds a type property from inherited type config', function () {
    expect(TypeProperty::fromInheritedTypeConfig([
        'model' => FeedItem::class,
        'name' => 'my_field',
        'types' => ['number', 'null'],
    ])->toString())->toBe('my_field: number | null;');
});
