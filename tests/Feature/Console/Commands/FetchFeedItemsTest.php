<?php

namespace Tests\Feature\Console\Commands;

use App\Console\Commands\FetchFeedItems;
use App\Models\Feed;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Kaishiyoku\HeraRssCrawler\HeraRssCrawler;
use Kaishiyoku\HeraRssCrawler\Models\Rss\Feed as RssFeed;
use Kaishiyoku\HeraRssCrawler\Models\Rss\FeedItem as RssFeedItem;
use ReflectionException;
use RuntimeException;
use Tests\TestCase;

class FetchFeedItemsTest extends TestCase
{
    public function test_fetch_feed_items_only_for_verified_users(): void
    {
        $expectedNumberOfFeedItems = 2;

        $unverifiedUser = User::factory()->unverified()->create();
        $unverifiedUserFeed = Feed::factory()->state(['feed_url' => 'https://feed.laravel-news.com/'])->recycle($unverifiedUser)->create();

        $verifiedUser = User::factory()->create();
        $verifiedUserFeed = Feed::factory()->state(['feed_url' => 'https://feed.laravel-news.com/'])->recycle($verifiedUser)->create();

        $heraRssCrawlerMock = $this->partialMock(HeraRssCrawler::class);
        $heraRssCrawlerMock->shouldReceive('parseFeed')->once()->andReturn(static::getDummyRssFeed($expectedNumberOfFeedItems));

        $this->artisan(FetchFeedItems::class)
            ->assertExitCode(Command::SUCCESS);

        static::assertSame(0, $unverifiedUserFeed->feedItems()->count());
        static::assertSame($expectedNumberOfFeedItems, $verifiedUserFeed->feedItems()->count());
    }

    public function test_invalid_rss_feed(): void
    {
        $user = User::factory()
            ->has(Feed::factory()->state(['feed_url' => 'https://laravel-news.com']))
            ->create();

        $heraRssCrawlerMock = $this->partialMock(HeraRssCrawler::class);
        $heraRssCrawlerMock->shouldReceive('parseFeed')->once()->andThrow(new RuntimeException());

        $this->artisan(FetchFeedItems::class)
            ->assertExitCode(Command::SUCCESS);

        static::assertSame(0, $user->feedItems()->count());
        static::assertNotNull($user->feeds()->first()->last_failed_at);
        static::assertNotNull($user->feeds()->first()->last_checked_at);
    }

    public function test_does_not_save_duplicate_feed_items(): void
    {
        $expectedNumberOfFeedItems = 2;

        $user = User::factory()->create();
        $feed = Feed::factory()->state(['feed_url' => 'https://feed.laravel-news.com/'])->recycle($user)->create();

        $heraRssCrawlerMock = $this->partialMock(HeraRssCrawler::class);
        $heraRssCrawlerMock->shouldReceive('parseFeed')->twice()->andReturn(static::getDummyRssFeed($expectedNumberOfFeedItems));

        $this->artisan(FetchFeedItems::class)
            ->assertExitCode(Command::SUCCESS);

        $numberOfFeedItems = $feed->feedItems()->count();

        static::assertSame($expectedNumberOfFeedItems, $numberOfFeedItems);

        $this->artisan(FetchFeedItems::class)
            ->assertExitCode(Command::SUCCESS);

        static::assertSame($numberOfFeedItems, $feed->feedItems()->count());
    }

    /**
     * @param int $numberOfFeedItems
     * @param Collection<RssFeedItem> $rssFeedItems
     * @return RssFeed
     * @throws ReflectionException
     */
    private static function getDummyRssFeed(int $numberOfFeedItems): RssFeed
    {
        $rssFeed = new RssFeed();

        $rssFeed->setCategories(collect());
        $rssFeed->setAuthors(collect());
        $rssFeed->setTitle('Dummy feed');
        $rssFeed->setCopyright(null);
        $rssFeed->setCreatedAt(now());
        $rssFeed->setUpdatedAt(now());
        $rssFeed->setDescription('Dummy feed description');
        $rssFeed->setFeedUrl('https://test.dev');
        $rssFeed->setId('dummy-feed');
        $rssFeed->setLanguage('en');
        $rssFeed->setUrl(null);
        $rssFeed->setFeedItems(collect(range(1, $numberOfFeedItems))->map(fn(int $id) => static::getDummyRssFeedItem($id)));
        $rssFeed->setChecksum(HeraRssCrawler::generateChecksumForFeed($rssFeed));

        return $rssFeed;
    }

    private static function getDummyRssFeedItem(int $id): RssFeedItem
    {
        $rssFeedItem = new RssFeedItem();
        $rssFeedItem->setCategories(collect());
        $rssFeedItem->setAuthors(collect());

        $rssFeedItem->setTitle("Dummy article #{$id}");
        $rssFeedItem->setCommentCount(0);
        $rssFeedItem->setCommentFeedLink('https://test.dev');
        $rssFeedItem->setCommentLink('https://test.dev');
        $rssFeedItem->setContent('Dummy content');
        $rssFeedItem->setCreatedAt(now());
        $rssFeedItem->setUpdatedAt(now());
        $rssFeedItem->setDescription('Dummy description');
        $rssFeedItem->setEnclosureUrl("https://test.dev/{$id}");
        $rssFeedItem->setImageUrls(collect());
        $rssFeedItem->setEncoding('utf-8');
        $rssFeedItem->setId("article-{$id}");
        $rssFeedItem->setLinks(collect());
        $rssFeedItem->setPermalink("https://test.dev/permalink/{$id}");
        $rssFeedItem->setType('Dummy article');
        $rssFeedItem->setXml(null);

        $rssFeedItem->generateChecksum();

        return $rssFeedItem;
    }
}
