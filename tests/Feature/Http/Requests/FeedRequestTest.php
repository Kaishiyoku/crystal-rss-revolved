<?php

use App\Http\Requests\StoreFeedRequest;
use App\Http\Requests\UpdateFeedRequest;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

use function Pest\Laravel\actingAs;

// Helpers
$makeData = fn (
    mixed $categoryId = null,
    mixed $feedUrl = null,
    mixed $siteUrl = null,
    mixed $faviconUrl = null,
    mixed $name = null,
    mixed $language = null,
    mixed $isPurgeable = null,
): array => [
    'category_id' => $categoryId ?? 1,
    'feed_url' => $feedUrl ?? 'https://tailwindcss.com/feeds/feed.xml',
    'site_url' => $siteUrl ?? 'https://tailwindcss.com/blog',
    'favicon_url' => $faviconUrl ?? 'https://tailwindcss.com/blog/favicons/apple-touch-icon.png?v=3',
    'name' => $name ?? 'Tailwind CSS Blog',
    'language' => $language ?? 'en',
    'is_purgeable' => $isPurgeable ?? true,
];

uses(RefreshDatabase::class);
beforeEach(function () {
    actingAs($user = User::factory()->create());

    Category::factory()->for($user)->state(['id' => fakeCategoryId()])->create();
    Category::factory()->state(['id' => fakeCategoryIdOfAnotherUser()])->create();
});

test('authorize', function () {
    expect((new StoreFeedRequest)->authorize())->toBeTrue()
        ->and((new UpdateFeedRequest)->authorize())->toBeTrue();
});

test('validate store', function (array $data, bool $shouldSucceed, ?string $expectedExceptionMessage = null) {
    $request = new StoreFeedRequest($data);

    if (! $shouldSucceed) {
        expect(fn () => $request->validate($request->rules()))
            ->toThrow(ValidationException::class, $expectedExceptionMessage);
    } else {
        $validated = $request->validate($request->rules());

        expect($validated)->toBe($data);
    }
})->with('validation');

test('validate update', function (array $data, bool $shouldSucceed, ?string $expectedExceptionMessage = null) {
    $request = new UpdateFeedRequest($data);

    if (! $shouldSucceed) {
        expect(fn () => $request->validate($request->rules()))
            ->toThrow(ValidationException::class, $expectedExceptionMessage);
    } else {
        $validated = $request->validate($request->rules());

        expect($validated)->toBe($data);
    }
})->with('validation');

// Datasets
dataset('validation', [
    'succeeds' => [
        $makeData(),
        true,
    ],
    'missing category_id' => [
        $makeData(categoryId: ''),
        false,
        'The Category field is required',
    ],
    'invalid category_id' => [
        $makeData(categoryId: '-'),
        false,
        'The Category field must be an integer.',
    ],
    'non-existing category_id' => [
        $makeData(categoryId: 999),
        false,
        'The selected Category is invalid.',
    ],
    'category_id of another user' => [
        $makeData(categoryId: fakeCategoryIdOfAnotherUser()),
        false,
        'The selected Category is invalid.',
    ],
    'missing feed_url' => [
        $makeData(feedUrl: ''),
        false,
        'The Feed URL field is required.',
    ],
    'invalid feed_url' => [
        $makeData(feedUrl: 'mailto:test@test.de'),
        false,
        'The Feed URL field must be a valid URL.',
    ],
    'overly long feed_url' => [
        $makeData(feedUrl: 'https://google.de/?test='.Str::random(232)),
        false,
        'The Feed URL field must not be greater than 255 characters.',
    ],
    'missing site_url' => [
        $makeData(siteUrl: ''),
        false,
        'The Site URL field is required.',
    ],
    'invalid site_url' => [
        $makeData(siteUrl: 'mailto:test@test.de'),
        false,
        'The Site URL field must be a valid URL.',
    ],
    'overly long site_url' => [
        $makeData(siteUrl: 'https://google.de/?test='.Str::random(232)),
        false,
        'The Site URL field must not be greater than 255 characters.',
    ],
    'nullable favicon_url' => [
        $makeData(faviconUrl: ''),
        true,
    ],
    'invalid favicon_url' => [
        $makeData(faviconUrl: 'mailto:test@test.de'),
        false,
        'The Favicon URL field must be a valid URL.',
    ],
    'overly long favicon_url' => [
        $makeData(faviconUrl: 'https://google.de/?test='.Str::random(232)),
        false,
        'The Favicon URL field must not be greater than 255 characters.',
    ],
    'missing name' => [
        $makeData(name: ''),
        false,
        'The Name field is required.',
    ],
    'invalid name' => [
        $makeData(name: 123),
        false,
        'The Name field must be a string.',
    ],
    'overly long name' => [
        $makeData(name: Str::random(256)),
        false,
        'The Name field must not be greater than 255 characters.',
    ],
    'missing language' => [
        $makeData(language: ''),
        false,
        'The Language field is required.',
    ],
    'invalid language' => [
        $makeData(language: 123),
        false,
        'The Language field must be a string.',
    ],
    'overly long language' => [
        $makeData(language: Str::random(256)),
        false,
        'The Language field must not be greater than 255 characters.',
    ],
    'missing is_purgeable' => [
        $makeData(isPurgeable: ''),
        false,
        'The Purgeable field is required.',
    ],
    'invalid is_purgeable' => [
        $makeData(isPurgeable: '#000000'),
        false,
        'The Purgeable field must be true or false.',
    ],
]);
