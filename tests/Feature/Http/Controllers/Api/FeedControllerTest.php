<?php

namespace Http\Controllers\Api;

use App\Http\Controllers\Api\FeedController;
use App\Models\Category;
use App\Models\Feed;
use App\Models\FeedItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;

class FeedControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_middleware_is_registered(): void
    {
        $middleware = collect((new FeedController())->getMiddleware())
            ->map(fn (array $arr) => Arr::get($arr, 'middleware'))
            ->toArray();

        $expectedMiddleware = [
            'can:viewAny,App\Models\Feed',
            'can:view,feed',
            'can:create,App\Models\Feed',
            'can:update,feed',
            'can:delete,feed',
        ];

        static::assertCount(5, $middleware);
        static::assertSame($expectedMiddleware, $middleware);
    }

    public function test_index(): void
    {
        $this->actingAs($user = User::factory()->create());

        $feed = Feed::factory()->for($user)->create();

        $this->json('get', route('api.feeds.index'))
            ->assertJson([
                'feeds' => [$feed->toArray()],
                'canCreate' => true,
            ]);
    }

    public function test_cannot_access_index_as_guest(): void
    {
        $this->json('get', route('api.feeds.index'))
            ->assertUnauthorized();
    }

    public function test_create(): void
    {
        $this->actingAs(User::factory()->create());

        $this->json('get', route('api.feeds.create'))
            ->assertJsonStructure([
                'categories',
            ]);
    }

    public function test_store(): void
    {
        $this->actingAs($user = User::factory()->create());

        $category = Category::factory()->for($user)->create();
        $feedUrl = 'https://tailwindcss.com/feeds/feed.xml';
        $siteUrl = 'https://tailwindcss.com/blog';
        $faviconUrl = 'https://tailwindcss.com/blog/favicons/apple-touch-icon.png?v=3';
        $name = 'Tailwind CSS Blog';
        $language = 'en';
        $isPurgeable = true;

        $this->json('post', route('api.feeds.store'), [
            'category_id' => $category->id,
            'feed_url' => $feedUrl,
            'site_url' => $siteUrl,
            'favicon_url' => $faviconUrl,
            'name' => $name,
            'language' => $language,
            'is_purgeable' => $isPurgeable,
        ])->assertJson([]);

        static::assertSame(1, $user->feeds()->count());
        static::assertSame($user->id, $user->feeds()->first()->user_id);
        static::assertSame($category->id, $user->feeds()->first()->category_id);
        static::assertSame($feedUrl, $user->feeds()->first()->feed_url);
        static::assertSame($siteUrl, $user->feeds()->first()->site_url);
        static::assertSame($faviconUrl, $user->feeds()->first()->favicon_url);
        static::assertSame($name, $user->feeds()->first()->name);
        static::assertSame($language, $user->feeds()->first()->language);
        static::assertSame($isPurgeable, $user->feeds()->first()->is_purgeable);
    }

    public function test_store_validation_fails_due_to_missing_data(): void
    {
        $this->actingAs($user = User::factory()->create());

        $this->json('post', route('api.feeds.store'), ['name' => ' '])
            ->assertUnprocessable()
            ->assertJson([
                'message' => 'The Category field is required. (and 5 more errors)',
                'errors' => [
                    'category_id' => ['The Category field is required.'],
                    'feed_url' => ['The Feed URL field is required.'],
                    'site_url' => ['The Site URL field is required.'],
                    'name' => ['The Name field is required.'],
                    'language' => ['The Language field is required.'],
                    'is_purgeable' => ['The Purgeable field is required.'],
                ],
            ]);
        static::assertSame(0, $user->feeds()->count());
    }

    public function test_edit(): void
    {
        $this->actingAs($user = User::factory()->create());

        $feed = Feed::factory()->for($user)->create();

        $this->json('get', route('api.feeds.edit', $feed))
            ->assertJson([
                'feed' => $feed->toArray(),
                'canDelete' => true,
            ]);
    }

    public function test_cannot_edit_feed_of_another_user(): void
    {
        $this->actingAs(User::factory()->create());

        $feed = Feed::factory()->create();

        $this->json('get', route('api.feeds.edit', $feed))
            ->assertForbidden();
    }

    public function test_update(): void
    {
        $this->actingAs($user = User::factory()->create());

        $feed = Feed::factory()->for($user)->create();

        $category = Category::factory()->for($user)->create();
        $feedUrl = 'https://tailwindcss.com/feeds/feed.xml/?updated';
        $siteUrl = 'https://tailwindcss.com/blog/?updated';
        $faviconUrl = 'https://tailwindcss.com/blog/favicons/apple-touch-icon.png?v=3&updated';
        $name = 'Tailwind CSS Blog (Updated)';
        $language = 'de';
        $isPurgeable = false;

        $this->json('put', route('api.feeds.update', $feed), [
            'category_id' => $category->id,
            'feed_url' => $feedUrl,
            'site_url' => $siteUrl,
            'favicon_url' => $faviconUrl,
            'name' => $name,
            'language' => $language,
            'is_purgeable' => $isPurgeable,
        ])->assertJson([]);

        static::assertSame($user->id, $user->feeds()->first()->user_id);
        static::assertSame($category->id, $user->feeds()->first()->category_id);
        static::assertSame($feedUrl, $user->feeds()->first()->feed_url);
        static::assertSame($siteUrl, $user->feeds()->first()->site_url);
        static::assertSame($faviconUrl, $user->feeds()->first()->favicon_url);
        static::assertSame($name, $user->feeds()->first()->name);
        static::assertSame($language, $user->feeds()->first()->language);
        static::assertSame($isPurgeable, $user->feeds()->first()->is_purgeable);
    }

    public function test_cannot_update_feed_of_another_user(): void
    {
        $this->actingAs(User::factory()->create());

        $feed = Feed::factory()->create();

        $this->json('put', route('api.feeds.update', $feed), ['name' => 'Test (updated)'])
            ->assertForbidden();
    }

    public function test_delete(): void
    {
        $this->actingAs($user = User::factory()->create());

        $feed = Feed::factory()->for($user)->hasFeedItems(10)->create();

        $this->json('delete', route('api.feeds.destroy', $feed))
            ->assertJson([]);
        static::assertSame(0, $user->feeds()->count());
        static::assertSame(0, $user->feedItems()->count());
    }

    public function test_cannot_delete_feed_of_another_user(): void
    {
        $this->actingAs(User::factory()->create());

        $feed = Feed::factory()->hasFeedItems(10)->create();

        $this->json('delete', route('api.feeds.destroy', $feed))
            ->assertForbidden();

        static::assertSame(1, Feed::count());
        static::assertSame(10, FeedItem::count());
    }
}
