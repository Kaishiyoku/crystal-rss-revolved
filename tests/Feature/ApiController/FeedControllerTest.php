<?php

namespace ApiController;

use App\Models\Category;
use App\Models\Feed;
use App\Models\FeedItem;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;
use Tests\TestCase;

class FeedControllerTest extends TestCase
{
    public function test_retrieves_resources()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $token = $user->createToken(Str::random(40), [
            'feed:read',
        ]);

        $response = $this->withToken($token->plainTextToken)->getJson(route('api.v1.feeds.index'));

        // since we haven't added any feeds the response should be empty
        static::assertEmpty($response->json());

        // add a feed and check that it is returned in the response
        $category = $user->categories()->save(Category::factory()->make());
        $feed = $user->feeds()->save(Feed::factory()->make()->fill(['category_id' => $category->id]));

        $response = $this->withToken($token->plainTextToken)->getJson(route('api.v1.feeds.index'));

        static::assertNotEmpty($response->json());
        static::assertIsArray($response->json());
        static::assertCount(1, $response->json());
        static::assertEquals($feed->user_id, $response->json('0.user_id'));
        static::assertEquals($feed->category_id, $response->json('0.category_id'));
        static::assertEquals($feed->feed_url, $response->json('0.feed_url'));
        static::assertEquals($feed->site_url, $response->json('0.site_url'));
        static::assertEquals($feed->favicon_url, $response->json('0.favicon_url'));
        static::assertEquals($feed->name, $response->json('0.name'));
        static::assertEquals($feed->language, $response->json('0.language'));
        static::assertEquals($feed->last_checked_at->toJSON(), $response->json('0.last_checked_at'));

        $response->assertOk();
    }

    public function test_creates_resource()
    {
        $user = User::factory()->create();
        $category = $user->categories()->save(Category::factory()->make());

        $feedData = [
            'category_id' => $category->id,
            'feed_url' => 'https://rss.golem.de/rss.php?feed=RSS2.0',
            'site_url' => 'https://www.golem.de/',
            'favicon_url' => 'https://www.golem.de/apple-touch-icon.png',
            'name' => 'Golem',
            'language' => 'de-DE',
        ];

        $this->actingAs($user, 'api');

        $token = $user->createToken(Str::random(40), [
            'feed:create',
        ]);

        $response = $this->withToken($token->plainTextToken)->postJson(route('api.v1.feeds.store'), $feedData);

        // one feed should be created
        static::assertCount(1, $user->categories);
        static::assertEquals(Arr::get($feedData, 'category_id'), $user->feeds->first()->category_id);
        static::assertEquals(Arr::get($feedData, 'feed_url'), $user->feeds->first()->feed_url);
        static::assertEquals(Arr::get($feedData, 'site_url'), $user->feeds->first()->site_url);
        static::assertEquals(Arr::get($feedData, 'favicon_url'), $user->feeds->first()->favicon_url);
        static::assertEquals(Arr::get($feedData, 'name'), $user->feeds->first()->name);
        static::assertEquals(Arr::get($feedData, 'language'), $user->feeds->first()->language);

        $response->assertOk();
    }

    public function test_updates_resource()
    {
        $user = User::factory()->create();
        $firstCategory = $user->categories()->save(Category::factory()->make());
        $secondCategory = $user->categories()->save(Category::factory()->make());
        $feed = $user->feeds()->save(Feed::factory()->make()->fill(['category_id' => $firstCategory->id]));

        $this->actingAs($user, 'api');

        $token = $user->createToken(Str::random(40), [
            'feed:update',
        ]);

        $updatedFeedData = [
            'category_id' => $secondCategory->id,
            'feed_url' => 'https://newsfeed.zeit.de/gesellschaft/index',
            'site_url' => 'https://www.zeit.de/gesellschaft/index',
            'favicon_url' => null,
            'name' => 'ZEIT ONLINE - Gesellschaft',
            'language' => 'de-DE',
        ];

        $response = $this->withToken($token->plainTextToken)->putJson(route('api.v1.feeds.update', $feed), $updatedFeedData);

        // the feed should be updated
        static::assertCount(1, $user->feeds);
        static::assertEquals($secondCategory->id, $response->json('category_id'));
        static::assertEquals(Arr::get($updatedFeedData, 'feed_url'), $response->json('feed_url'));
        static::assertEquals(Arr::get($updatedFeedData, 'site_url'), $response->json('site_url'));
        static::assertEquals(Arr::get($updatedFeedData, 'favicon_url'), $response->json('favicon_url'));
        static::assertEquals(Arr::get($updatedFeedData, 'name'), $response->json('name'));
        static::assertEquals(Arr::get($updatedFeedData, 'language'), $response->json('language'));

        $response->assertOk();
    }

    public function test_deletes_resource()
    {
        $user = User::factory()->create();
        $category = $user->categories()->save(Category::factory()->make());
        $feed = $user->feeds()->save(Feed::factory()->make()->fill(['category_id' => $category->id]));

        $this->actingAs($user, 'api');

        $token = $user->createToken(Str::random(40), [
            'feed:delete',
        ]);

        $response = $this->withToken($token->plainTextToken)->deleteJson(route('api.v1.feeds.destroy', $feed));

        // there should be no feeds
        static::assertEmpty($user->feeds);

        $response->assertOk();
    }

    public function test_shows_resource()
    {
        $user = User::factory()->create();
        $category = $user->categories()->save(Category::factory()->make());
        $feed = $user->feeds()->save(Feed::factory()->make()->fill(['category_id' => $category->id]));

        $this->actingAs($user, 'api');

        $token = $user->createToken(Str::random(40), [
            'feed:read',
        ]);

        $response = $this->withToken($token->plainTextToken)->getJson(route('api.v1.feeds.show', $feed));

        static::assertEquals($feed->id, $response->json('id'));
        static::assertEquals($feed->user_id, $response->json('user_id'));
        static::assertEquals($feed->category_id, $response->json('category_id'));
        static::assertEquals($feed->feed_url, $response->json('feed_url'));
        static::assertEquals($feed->site_url, $response->json('site_url'));
        static::assertEquals($feed->favicon_url, $response->json('favicon_url'));
        static::assertEquals($feed->name, $response->json('name'));
        static::assertEquals($feed->language, $response->json('language'));
        static::assertEquals($feed->last_checked_at->toJSON(), $response->json('last_checked_at'));

        $response->assertOk();
    }

    public function test_marks_all_as_read()
    {
        $user = User::factory()->create();
        $category = $user->categories()->save(Category::factory()->make());
        $feed = $user->feeds()->save(Feed::factory()->make()->fill(['category_id' => $category->id]));
        $feed->feedItems()->saveMany(FeedItem::factory()->times(10)->make());

        $token = $user->createToken(Str::random(40), [
            'feed:read',
        ]);

        $responseAuthorized = $this->actingAs($user, 'api')->withToken($token->plainTextToken)->putJson(route('api.v1.feeds.mark_all_as_read'));

        $responseAuthorized->assertOk();

        // every feed item should be marked as read
        $user->feedItems->each(function (FeedItem $feedItem) {
            static::assertNotNull($feedItem->read_at);
        });
    }

    public function test_requires_authorization()
    {
        $response = $this->getJson(route('api.v1.feeds.index'));
        $response->assertUnauthorized();

        $response = $this->postJson(route('api.v1.feeds.store'));
        $response->assertUnauthorized();

        $response = $this->putJson(route('api.v1.feeds.update', 1));
        $response->assertUnauthorized();

        $response = $this->getJson(route('api.v1.feeds.show', 1));
        $response->assertUnauthorized();

        $response = $this->deleteJson(route('api.v1.feeds.destroy', 1));
        $response->assertUnauthorized();

        $response = $this->putJson(route('api.v1.feeds.mark_all_as_read', 1));
        $response->assertUnauthorized();
    }

    public function test_requires_token_permissions()
    {
        $user = User::factory()->create();
        $category = $user->categories()->save(Category::factory()->make());
        $feed = $user->feeds()->save(Feed::factory()->make()->fill(['category_id' => $category->id]));

        $token = $user->createToken(Str::random(40), []);

        $this->actingAs($user, 'api');

        $response = $this->withToken($token->plainTextToken)->getJson(route('api.v1.feeds.index'));
        $response->assertForbidden();

        $response = $this->withToken($token->plainTextToken)->postJson(route('api.v1.feeds.store'));
        $response->assertForbidden();

        $response = $this->withToken($token->plainTextToken)->putJson(route('api.v1.feeds.update', $feed));
        $response->assertForbidden();

        $response = $this->withToken($token->plainTextToken)->getJson(route('api.v1.feeds.show', $feed));
        $response->assertForbidden();

        $response = $this->withToken($token->plainTextToken)->deleteJson(route('api.v1.feeds.destroy', $feed));
        $response->assertForbidden();

        $response = $this->withToken($token->plainTextToken)->putJson(route('api.v1.feeds.mark_all_as_read', $feed));
        $response->assertForbidden();
    }

    public function test_api_token_permissions_tests()
    {
        $user = User::factory()->create();

        $responseUnauthorized = $this->actingAs($user, 'api')->getJson(route('api.v1.feeds.index'));

        $responseUnauthorized->assertUnauthorized();

        $token = $user->createToken(Str::random(40), [
            'feed:create',
            'feed:read',
            'feed:update',
            'feed:delete',
            'feed:mark-all-as-read',
        ]);

        $responseAuthorized = $this->actingAs($user, 'api')->withToken($token->plainTextToken)->getJson(route('api.v1.feeds.index'));

        $responseAuthorized->assertOk();
    }
}
