<?php

namespace Http\Requests;

use App\Http\Requests\FeedDiscovererRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class FeedDiscovererRequestTest extends TestCase
{
    public function test_authorize(): void
    {
        static::assertTrue((new FeedDiscovererRequest)->authorize());
    }

    /**
     * @dataProvider validationDataProvider
     */
    public function test_validate(array $data, bool $shouldSucceed, ?string $expectedExceptionMessage = null): void
    {
        $request = new FeedDiscovererRequest($data);

        if (! $shouldSucceed) {
            static::expectException(ValidationException::class);
            static::expectExceptionMessage($expectedExceptionMessage);
        }

        $validated = $request->validate($request->rules());

        if ($shouldSucceed) {
            static::assertSame($data, $validated);
        }
    }

    public static function validationDataProvider(): array
    {
        return [
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
        ];
    }

    private static function makeData(mixed $feedUrl): array
    {
        return [
            'feed_url' => $feedUrl,
        ];
    }
}
