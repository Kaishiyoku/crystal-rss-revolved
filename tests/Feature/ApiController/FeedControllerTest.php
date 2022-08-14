<?php

namespace ApiController;

use App\Models\Category;
use App\Models\Feed;
use App\Models\FeedItem;
use App\Models\User;
use Illuminate\Support\Arr;
use Tests\TestCase;

class FeedControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_retrieves_resources()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->getJson(route('api.v1.feeds.index'));

        // since we haven't added any feeds the response should be empty
        static::assertEmpty($response->json());

        // add a feed and check that it is returned in the response
        $category = $user->categories()->save(Category::factory()->make());
        $feed = $user->feeds()->save(Feed::factory()->make()->fill(['category_id' => $category->id]));

        $response = $this->getJson(route('api.v1.feeds.index'));

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

    /**
     * A basic feature test example.
     *
     * @return void
     */
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

        $this->actingAs($user);

        $response = $this->postJson(route('api.v1.feeds.store'), $feedData);

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

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_updates_resource()
    {
        $user = User::factory()->create();
        $firstCategory = $user->categories()->save(Category::factory()->make());
        $secondCategory = $user->categories()->save(Category::factory()->make());
        $feed = $user->feeds()->save(Feed::factory()->make()->fill(['category_id' => $firstCategory->id]));

        $this->actingAs($user);

        $updatedFeedData = [
            'category_id' => $secondCategory->id,
            'feed_url' => 'https://newsfeed.zeit.de/gesellschaft/index',
            'site_url' => 'https://www.zeit.de/gesellschaft/index',
            'favicon_url' => null,
            'name' => 'ZEIT ONLINE - Gesellschaft',
            'language' => 'de-DE',
        ];

        $response = $this->putJson(route('api.v1.feeds.update', $feed), $updatedFeedData);

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

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_deletes_resource()
    {
        $user = User::factory()->create();
        $category = $user->categories()->save(Category::factory()->make());
        $feed = $user->feeds()->save(Feed::factory()->make()->fill(['category_id' => $category->id]));

        $this->actingAs($user);

        $response = $this->deleteJson(route('api.v1.feeds.destroy', $feed));

        // there should be no feeds
        static::assertEmpty($user->feeds);

        $response->assertOk();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_shows_resource()
    {
        $user = User::factory()->create();
        $category = $user->categories()->save(Category::factory()->make());
        $feed = $user->feeds()->save(Feed::factory()->make()->fill(['category_id' => $category->id]));

        $this->actingAs($user);

        $response = $this->getJson(route('api.v1.feeds.show', $feed));

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

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_marks_all_as_read()
    {
        $user = User::factory()->create();
        $category = $user->categories()->save(Category::factory()->make());
        $feed = $user->feeds()->save(Feed::factory()->make()->fill(['category_id' => $category->id]));
        $feed->feedItems()->saveMany(FeedItem::factory()->times(10)->make());

        $this->actingAs($user);

        $response = $this->putJson(route('api.v1.feeds.mark_all_as_read', $feed));

        // every feed item should be marked as read
        $user->feedItems->each(function (FeedItem $feedItem) {
            static::assertNotNull($feedItem->read_at);
        });

        $response->assertOk();
    }
}
