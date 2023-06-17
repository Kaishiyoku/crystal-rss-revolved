<?php

namespace Tests\Feature\Requests;

use App\Http\Requests\StoreFeedRequest;
use App\Http\Requests\UpdateFeedRequest;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class FeedRequestTest extends TestCase
{
    use RefreshDatabase;

    private const CATEGORY_ID = 1;

    private const CATEGORY_ID_OF_ANOTHER_USER = 2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs($user = User::factory()->create());

        Category::factory()->for($user)->state(['id' => static::CATEGORY_ID])->create();
        Category::factory()->state(['id' => static::CATEGORY_ID_OF_ANOTHER_USER])->create();
    }

    public function test_authorize(): void
    {
        static::assertTrue((new StoreFeedRequest())->authorize());
        static::assertTrue((new UpdateFeedRequest())->authorize());
    }

    /**
     * @dataProvider rulesDataProvider
     */
    public function test_store_rules(array $request, bool $shouldSucceed, string $expectedExceptionMessage = null): void
    {
        $storeFeedRequest = new StoreFeedRequest([], $request);

        if (! $shouldSucceed) {
            static::expectException(ValidationException::class);
            static::expectExceptionMessage($expectedExceptionMessage);
        }

        $validated = Validator::validate($request, $storeFeedRequest->rules());

        if ($shouldSucceed) {
            static::assertSame($request, $validated);
        }
    }

    /**
     * @dataProvider rulesDataProvider
     */
    public function test_update_rules(array $request, bool $shouldSucceed, string $expectedExceptionMessage = null): void
    {
        $updateFeedRequest = new UpdateFeedRequest([], $request);

        if (! $shouldSucceed) {
            static::expectException(ValidationException::class);
            static::expectExceptionMessage($expectedExceptionMessage);
        }

        $validated = Validator::validate($request, $updateFeedRequest->rules());

        if ($shouldSucceed) {
            static::assertSame($request, $validated);
        }
    }

    public static function rulesDataProvider(): array
    {
        return [
            'succeeds' => [
                static::makeRequest(),
                true,
            ],
            'missing category_id' => [
                static::makeRequest(categoryId: ''),
                false,
                'The Category field is required',
            ],
            'invalid category_id' => [
                static::makeRequest(categoryId: '-'),
                false,
                'The Category must be an integer.',
            ],
            'non-existing category_id' => [
                static::makeRequest(categoryId: 999),
                false,
                'The selected Category is invalid.',
            ],
            'category_id of another user' => [
                static::makeRequest(categoryId: static::CATEGORY_ID_OF_ANOTHER_USER),
                false,
                'The selected Category is invalid.',
            ],
            'missing feed_url' => [
                static::makeRequest(feedUrl: ''),
                false,
                'The Feed URL field is required.',
            ],
            'invalid feed_url' => [
                static::makeRequest(feedUrl: 'mailto:test@test.de'),
                false,
                'The Feed URL format is invalid.',
            ],
            'overly long feed_url' => [
                static::makeRequest(feedUrl: 'https://google.de/?test='.Str::random(232)),
                false,
                'The Feed URL may not be greater than 255 characters.',
            ],
            'missing site_url' => [
                static::makeRequest(siteUrl: ''),
                false,
                'The Site URL field is required.',
            ],
            'invalid site_url' => [
                static::makeRequest(siteUrl: 'mailto:test@test.de'),
                false,
                'The Site URL format is invalid.',
            ],
            'overly long site_url' => [
                static::makeRequest(siteUrl: 'https://google.de/?test='.Str::random(232)),
                false,
                'The Site URL may not be greater than 255 characters.',
            ],
            'nullable favicon_url' => [
                static::makeRequest(faviconUrl: ''),
                true,
            ],
            'invalid favicon_url' => [
                static::makeRequest(faviconUrl: 'mailto:test@test.de'),
                false,
                'The Favicon URL format is invalid.'
            ],
            'overly long favicon_url' => [
                static::makeRequest(faviconUrl: 'https://google.de/?test='.Str::random(232)),
                false,
                'The Favicon URL may not be greater than 255 characters.',
            ],
            'missing name' => [
                static::makeRequest(name: ''),
                false,
                'The Name field is required.',
            ],
            'invalid name' => [
                static::makeRequest(name: 123),
                false,
                'The Name must be a string.',
            ],
            'overly long name' => [
                static::makeRequest(name: Str::random(256)),
                false,
                'The Name may not be greater than 255 characters.',
            ],
            'missing language' => [
                static::makeRequest(language: ''),
                false,
                'The Language field is required.',
            ],
            'invalid language' => [
                static::makeRequest(language: 123),
                false,
                'The Language must be a string.',
            ],
            'overly long language' => [
                static::makeRequest(language: Str::random(256)),
                false,
                'The Language may not be greater than 255 characters.',
            ],
            'missing is_purgeable' => [
                static::makeRequest(isPurgeable: ''),
                false,
                'The Purgeable field is required.',
            ],
            'invalid is_purgeable' => [
                static::makeRequest(isPurgeable: '#000000'),
                false,
                'The Purgeable field must be true or false.',
            ],
        ];
    }

    private static function makeRequest(
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
            'name' => $name ?? 'https://tailwindcss.com/feeds/feed.xml',
            'language' => $language ?? 'en',
            'is_purgeable' => $isPurgeable ?? true,
        ];
    }
}
