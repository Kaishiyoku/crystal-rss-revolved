<?php

namespace Tests\Feature\Models;

use App\Models\Feed;
use App\Models\FeedItem;
use Illuminate\Console\Command;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class FeedItemTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_feed_item_belongs_to_feed(): void
    {
        $feed = Feed::factory()->create();
        $feedItem = FeedItem::factory()->for($feed)->create();

        static::assertSame($feed->id, $feedItem->feed->id);
    }

    public function test_unread_scope(): void
    {
        $unreadFeedItemIds = FeedItem::factory(5)->state(['read_at' => null])->create()->pluck('id');
        FeedItem::factory(5)->state(['read_at' => now()])->create()->pluck('id');

        static::assertEquals($unreadFeedItemIds, FeedItem::unread()->pluck('id'));
    }

    public function test_of_feed_scope(): void
    {
        $feedA = Feed::factory()->create();
        $feedItemIdsOfFeedA = FeedItem::factory(5)->for($feedA)->create()->pluck('id');

        $feedB = Feed::factory()->create();
        $feedItemIdsOfFeedB = FeedItem::factory(5)->for($feedB)->create()->pluck('id');

        static::assertEquals(
            $feedItemIdsOfFeedA->sortByDesc('posted_at'),
            FeedItem::ofFeed($feedA->id)->pluck('id')
        );
        static::assertEquals(
            $feedItemIdsOfFeedB->sortByDesc('posted_at'),
            FeedItem::ofFeed($feedB->id)->pluck('id')
        );
        static::assertEqualsCanonicalizing(
            $feedItemIdsOfFeedA->merge($feedItemIdsOfFeedB),
            FeedItem::ofFeed(null)->pluck('id')
        );
    }

    public function test_has_image_attribute(): void
    {
        $feedItemWithImage = FeedItem::factory()
            ->state([
                'image_url' => $this->faker()->imageUrl(),
                'image_mimetype' => $this->faker()->randomElement(['image/png', 'image/jpeg']),
            ])
            ->create();

        $feedItemWithoutImage = FeedItem::factory()
            ->state([
                'image_url' => null,
                'image_mimetype' => null,
            ])
            ->create();

        $feedItemWithInvalidImageMimetype = FeedItem::factory()
            ->state([
                'image_url' => $this->faker()->imageUrl(),
                'image_mimetype' => 'text/plain',
            ])
            ->create();

        $feedItems = FeedItem::factory(20)->create();

        static::assertTrue($feedItemWithImage->has_image);
        static::assertFalse($feedItemWithoutImage->has_image);
        static::assertFalse($feedItemWithInvalidImageMimetype->has_image);

        $feedItems->each(function (FeedItem $feedItem) {
            static::assertSame((bool) $feedItem->image_url, $feedItem->has_image);
        });
    }

    public function test_per_page(): void
    {
        $expectedPerPage = 20;
        $feedItem = FeedItem::factory()->create();

        Config::set('app.feed_items_per_page', $expectedPerPage);

        static::assertSame($expectedPerPage, $feedItem->getPerPage());
    }

    public function test_prunable(): void
    {
        $this->freezeTime();

        $prunableFeedItemIds = FeedItem::factory(10)->state(['read_at' => now()->subMonths(5)])->create()->pluck('id');
        $notPrunableFeedItemIds = FeedItem::factory(10)->state(['read_at' => now()->subMonths(5)->addSecond()])->create()->pluck('id');

        Config::set('app.months_after_pruning_feed_items', 5);

        static::assertEquals($prunableFeedItemIds, (new FeedItem())->prunable()->pluck('id'));
        static::assertNotContains($notPrunableFeedItemIds, (new FeedItem())->prunable()->pluck('id'));

        $this->artisan('model:prune')
            ->assertExitCode(Command::SUCCESS);

        static::assertEquals($notPrunableFeedItemIds, FeedItem::pluck('id'));
    }
}
