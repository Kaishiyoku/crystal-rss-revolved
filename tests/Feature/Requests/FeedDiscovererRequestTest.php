<?php

namespace Requests;

use App\Http\Requests\FeedDiscovererRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class FeedDiscovererRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_authorize(): void
    {
        static::assertTrue((new FeedDiscovererRequest())->authorize());
    }

    /**
     * @dataProvider rulesDataProvider
     */
    public function test_rules(array $request, bool $shouldSucceed, string $expectedExceptionMessage = null): void
    {
        $feedDiscovererRequest = new FeedDiscovererRequest([], $request);

        if (! $shouldSucceed) {
            static::expectException(ValidationException::class);
            static::expectExceptionMessage($expectedExceptionMessage);
        }

        $validated = Validator::validate($request, $feedDiscovererRequest->rules());

        if ($shouldSucceed) {
            static::assertSame($request, $validated);
        }
    }

    public static function rulesDataProvider(): array
    {
        return [
            'succeeds' => [
                static::makeRequest('https://tailwindcss.com/feeds/feed.xml'),
                true,
            ],
            'missing feed_url' => [
                static::makeRequest(''),
                false,
                'The Feed URL field is required.',
            ],
            'invalid feed_url' => [
                static::makeRequest('mailto:test@test.de'),
                false,
                'The Feed URL format is invalid.'
            ],
            'overly long feed_url' => [
                static::makeRequest('https://google.de/?test='.Str::random(232)),
                false,
                'The Feed URL may not be greater than 255 characters.',
            ],
        ];
    }

    private static function makeRequest(mixed $feedUrl): array {
        return [
            'feed_url' => $feedUrl,
        ];
    }
}
