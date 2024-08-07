<?php

use App\Console\Commands\GenerateTypeScriptModels;

use function Pest\Laravel\artisan;

it('generates TypeScript types', function () {
    artisan(GenerateTypeScriptModels::class)
        ->assertSuccessful();

    // check if code has been generated
    expect(array_values(array_diff((array) scandir(config('type-script-model-generator.output_directory')), ['..', '.'])))->toBe([
        'Category.ts',
        'CategoryWithFeedsCount.ts',
        'Feed.ts',
        'FeedItem.ts',
        'FeedWithFeedItemsCount.ts',
        'ShortFeed.ts',
        'ShortFeedWithFeedItemsCount.ts',
        'User.ts',
        'UserWithStats.ts',
    ]);
});
