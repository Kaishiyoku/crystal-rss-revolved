<?php

use App\Http\Requests\FeedDiscovererRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

// Helpers
$makeData = fn (mixed $feedUrl): array => [
    'feed_url' => $feedUrl,
];

test('authorize', function () {
    expect((new FeedDiscovererRequest)->authorize())->toBeTrue();
});

test('validate', function (array $data, bool $shouldSucceed, ?string $expectedExceptionMessage = null) {
    $request = new FeedDiscovererRequest($data);

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
        $makeData('https://tailwindcss.com/feeds/feed.xml'),
        true,
    ],
    'missing feed_url' => [
        $makeData(''),
        false,
        'The Feed URL field is required.',
    ],
    'invalid feed_url' => [
        $makeData('mailto:test@test.de'),
        false,
        'The Feed URL field must be a valid URL.',
    ],
    'overly long feed_url' => [
        $makeData('https://google.de/?test='.Str::random(232)),
        false,
        'The Feed URL field must not be greater than 255 characters.',
    ],
]);
