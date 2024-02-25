<?php

use App\Models\Feed;
use App\Services\TypeScriptModelGenerator\TypeScriptModelGenerator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Config;

it('generates types for a model', closure: function () {
    $files = new Filesystem();
    $customOutputDirectory = './test/resources/js/types/generated/Models';

    Config::set('type-script-model-generator.output_directory', $customOutputDirectory);

    (new TypeScriptModelGenerator())->generateModel(Feed::class);

    expect(array_values(array_diff(scandir($customOutputDirectory), ['..', '.'])))->toBe([
        'Feed.ts',
        'FeedWithFeedItemsCount.ts',
        'ShortFeed.ts',
    ]);

    $files->deleteDirectory($customOutputDirectory);

    Config::set('type-script-model-generator.output_directory', null);
});
