<?php

use App\Http\Requests\FeedUrlDiscovererRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

uses(TestCase::class);

test('authorize', function () {
    static::assertTrue((new FeedUrlDiscovererRequest)->authorize());
});

test('validate', function (array $data, bool $shouldSucceed, ?string $expectedExceptionMessage = null) {
    $request = new FeedUrlDiscovererRequest($data);

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
        static::makeData('https://tailwindcss.com/feeds/feed.xml'),
        true,
    ],
    'missing feed_url' => [
        static::makeData(''),
        false,
        'The Feed URL field is required.',
    ],
    'invalid feed_url' => [
        static::makeData('mailto:test@test.de'),
        false,
        'The Feed URL field must be a valid URL.',
    ],
    'overly long feed_url' => [
        static::makeData('https://google.de/?test='.Str::random(232)),
        false,
        'The Feed URL field must not be greater than 255 characters.',
    ],
]);

// Helpers
function makeData(mixed $feedUrl): array
{
    return [
        'feed_url' => $feedUrl,
    ];
}
