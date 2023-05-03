<?php

namespace Models;

use App\Models\Category;
use App\Models\Feed;
use App\Models\FeedItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeedTest extends TestCase
{
    use RefreshDatabase;

    public function test_feed_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $feed = Feed::factory()->for($user)->create();

        static::assertSame($user->id, $feed->user->id);
    }

    public function test_feed_belongs_to_category(): void
    {
        $category = Category::factory()->create();
        $feed = Feed::factory()->for($category)->create();

        static::assertSame($category->id, $feed->category->id);
    }

    public function test_feed_has_feed_items(): void
    {
        $feed = Feed::factory()->create();
        $feedItems = FeedItem::factory(5)->for($feed)->create();

        static::assertEquals(
            $feedItems->sortByDesc('posted_at')->pluck('id'),
            $feed->feedItems()->pluck('id'),
        );
    }
}
