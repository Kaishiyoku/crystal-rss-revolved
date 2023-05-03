<?php

namespace Tests\Feature\Console\Commands;

use App\Console\Commands\FetchFeedItems;
use App\Models\Category;
use App\Models\Feed;
use App\Models\FeedItem;
use App\Models\User;
use Illuminate\Console\Command;
use Tests\TestCase;

class FetchFeedItemsTest extends TestCase
{
    public function test_fetch_feed_items_only_for_verified_users(): void
    {
        $unverifiedUser = User::factory()->unverified()->create();
        $unverifiedUserFeed = Feed::factory()->state(['feed_url' => 'https://feed.laravel-news.com/'])->recycle($unverifiedUser)->create();

        $verifiedUser = User::factory()->create();
        $verifiedUserFeed = Feed::factory()->state(['feed_url' => 'https://feed.laravel-news.com/'])->recycle($verifiedUser)->create();

        $this->artisan(FetchFeedItems::class)
            ->assertExitCode(Command::SUCCESS);

        static::assertSame(0, $unverifiedUserFeed->feedItems()->count());
        static::assertGreaterThan(0, $verifiedUserFeed->feedItems()->count());
    }

    public function test_invalid_rss_feed(): void
    {
        $user = User::factory()
            ->has(Feed::factory()->state(['feed_url' => 'https://laravel-news.com']))
            ->create();

        $this->artisan(FetchFeedItems::class)
            ->assertExitCode(Command::SUCCESS);

        static::assertSame(0, $user->feedItems()->count());
        static::assertNotNull($user->feeds()->first()->last_failed_at);
        static::assertNotNull($user->feeds()->first()->last_checked_at);
    }

    public function test_does_not_save_duplicate_feed_items(): void
    {
        $user = User::factory()->create();
        $feed = Feed::factory()->state(['feed_url' => 'https://feed.laravel-news.com/'])->recycle($user)->create();

        $this->artisan(FetchFeedItems::class)
            ->assertExitCode(Command::SUCCESS);

        $numberOfFeedItems = $feed->feedItems()->count();

        static::assertGreaterThan(0, $numberOfFeedItems);

        $this->artisan(FetchFeedItems::class)
            ->assertExitCode(Command::SUCCESS);

        static::assertSame($numberOfFeedItems, $feed->feedItems()->count());
    }
}
