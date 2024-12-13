<?php

use App\Http\Requests\StoreFeedRequest;
use App\Http\Requests\UpdateFeedRequest;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->actingAs($user = User::factory()->create());

    Category::factory()->for($user)->state(['id' => static::CATEGORY_ID])->create();
    Category::factory()->state(['id' => static::CATEGORY_ID_OF_ANOTHER_USER])->create();
});


test('authorize', function () {
    static::assertTrue((new StoreFeedRequest)->authorize());
    static::assertTrue((new UpdateFeedRequest)->authorize());
});

test('validate store', function (array $data, bool $shouldSucceed, ?string $expectedExceptionMessage = null) {
    $request = new StoreFeedRequest($data);

    if (! $shouldSucceed) {
        static::expectException(ValidationException::class);
        static::expectExceptionMessage($expectedExceptionMessage);
    }

    $validated = $request->validate($request->rules());

    if ($shouldSucceed) {
        static::assertSame($data, $validated);
    }
})->with('validation');

test('validate update', function (array $data, bool $shouldSucceed, ?string $expectedExceptionMessage = null) {
    $request = new UpdateFeedRequest($data);

    if (! $shouldSucceed) {
        static::expectException(ValidationException::class);
        static::expectExceptionMessage($expectedExceptionMessage);
    }

    $validated = $request->validate($request->rules());

    if ($shouldSucceed) {
        static::assertSame($data, $validated);
    }
})->with('validation');

// Datasets
dataset('validation', [
    'succeeds' => [
        static::makeData(),
        true,
    ],
    'missing category_id' => [
        static::makeData(categoryId: ''),
        false,
        'The Category field is required',
    ],
    'invalid category_id' => [
        static::makeData(categoryId: '-'),
        false,
        'The Category field must be an integer.',
    ],
    'non-existing category_id' => [
        static::makeData(categoryId: 999),
        false,
        'The selected Category is invalid.',
    ],
    'category_id of another user' => [
        static::makeData(categoryId: static::CATEGORY_ID_OF_ANOTHER_USER),
        false,
        'The selected Category is invalid.',
    ],
    'missing feed_url' => [
        static::makeData(feedUrl: ''),
        false,
        'The Feed URL field is required.',
    ],
    'invalid feed_url' => [
        static::makeData(feedUrl: 'mailto:test@test.de'),
        false,
        'The Feed URL field must be a valid URL.',
    ],
    'overly long feed_url' => [
        static::makeData(feedUrl: 'https://google.de/?test='.Str::random(232)),
        false,
        'The Feed URL field must not be greater than 255 characters.',
    ],
    'missing site_url' => [
        static::makeData(siteUrl: ''),
        false,
        'The Site URL field is required.',
    ],
    'invalid site_url' => [
        static::makeData(siteUrl: 'mailto:test@test.de'),
        false,
        'The Site URL field must be a valid URL.',
    ],
    'overly long site_url' => [
        static::makeData(siteUrl: 'https://google.de/?test='.Str::random(232)),
        false,
        'The Site URL field must not be greater than 255 characters.',
    ],
    'nullable favicon_url' => [
        static::makeData(faviconUrl: ''),
        true,
    ],
    'invalid favicon_url' => [
        static::makeData(faviconUrl: 'mailto:test@test.de'),
        false,
        'The Favicon URL field must be a valid URL.',
    ],
    'overly long favicon_url' => [
        static::makeData(faviconUrl: 'https://google.de/?test='.Str::random(232)),
        false,
        'The Favicon URL field must not be greater than 255 characters.',
    ],
    'missing name' => [
        static::makeData(name: ''),
        false,
        'The Name field is required.',
    ],
    'invalid name' => [
        static::makeData(name: 123),
        false,
        'The Name field must be a string.',
    ],
    'overly long name' => [
        static::makeData(name: Str::random(256)),
        false,
        'The Name field must not be greater than 255 characters.',
    ],
    'missing language' => [
        static::makeData(language: ''),
        false,
        'The Language field is required.',
    ],
    'invalid language' => [
        static::makeData(language: 123),
        false,
        'The Language field must be a string.',
    ],
    'overly long language' => [
        static::makeData(language: Str::random(256)),
        false,
        'The Language field must not be greater than 255 characters.',
    ],
    'missing is_purgeable' => [
        static::makeData(isPurgeable: ''),
        false,
        'The Purgeable field is required.',
    ],
    'invalid is_purgeable' => [
        static::makeData(isPurgeable: '#000000'),
        false,
        'The Purgeable field must be true or false.',
    ],
]);

// Helpers
function makeData(
    mixed $categoryId = null,
    mixed $feedUrl = null,
    mixed $siteUrl = null,
    mixed $faviconUrl = null,
    mixed $name = null,
    mixed $language = null,
    mixed $isPurgeable = null,
): array {
    return [
        'category_id' => $categoryId ?? static::CATEGORY_ID,
        'feed_url' => $feedUrl ?? 'https://tailwindcss.com/feeds/feed.xml',
        'site_url' => $siteUrl ?? 'https://tailwindcss.com/blog',
        'favicon_url' => $faviconUrl ?? 'https://tailwindcss.com/blog/favicons/apple-touch-icon.png?v=3',
        'name' => $name ?? 'Tailwind CSS Blog',
        'language' => $language ?? 'en',
        'is_purgeable' => $isPurgeable ?? true,
    ];
}
