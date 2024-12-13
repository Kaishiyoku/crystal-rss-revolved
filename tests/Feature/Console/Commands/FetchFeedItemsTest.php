<?php

use App\Console\Commands\FetchFeedItems;
use App\Models\Feed;
use App\Models\FeedItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Kaishiyoku\HeraRssCrawler\HeraRssCrawler;
use Kaishiyoku\HeraRssCrawler\Models\Rss\Feed as RssFeed;
use Kaishiyoku\HeraRssCrawler\Models\Rss\FeedItem as RssFeedItem;

uses(RefreshDatabase::class);

test('fetch feed items only for verified users', function () {
    $expectedNumberOfFeedItems = 2;

    $unverifiedUser = User::factory()->unverified()->create();
    $unverifiedUserFeed = Feed::factory()->state(['feed_url' => 'https://feed.laravel-news.com/'])->recycle($unverifiedUser)->create();

    $verifiedUser = User::factory()->create();
    $verifiedUserFeed = Feed::factory()->state(['feed_url' => 'https://feed.laravel-news.com/'])->recycle($verifiedUser)->create();

    $heraRssCrawlerMock = $this->partialMock(HeraRssCrawler::class);
    $heraRssCrawlerMock->shouldReceive('parseFeed')->once()->andReturn(getDummyRssFeed($expectedNumberOfFeedItems));

    $this->artisan(FetchFeedItems::class)
        ->assertExitCode(Command::SUCCESS);

    static::assertSame(0, $unverifiedUserFeed->feedItems()->count());
    static::assertSame($expectedNumberOfFeedItems, $verifiedUserFeed->feedItems()->count());
});

test('invalid rss feed', function () {
    $user = User::factory()
        ->has(Feed::factory()->state(['feed_url' => 'https://laravel-news.com']))
        ->create();

    $heraRssCrawlerMock = $this->partialMock(HeraRssCrawler::class);
    $heraRssCrawlerMock->shouldReceive('parseFeed')->once()->andThrow(new RuntimeException);

    $this->artisan(FetchFeedItems::class)
        ->assertExitCode(Command::SUCCESS);

    static::assertSame(0, $user->feedItems()->count());
    static::assertNotNull($user->feeds()->first()->last_failed_at);
    static::assertNotNull($user->feeds()->first()->last_checked_at);
});

test('does not save duplicate feed items', function () {
    $expectedNumberOfFeedItems = 2;

    $user = User::factory()->create();
    $feed = Feed::factory()->state(['feed_url' => 'https://feed.laravel-news.com/'])->recycle($user)->create();

    $heraRssCrawlerMock = $this->partialMock(HeraRssCrawler::class);
    $heraRssCrawlerMock->shouldReceive('parseFeed')->twice()->andReturn(getDummyRssFeed($expectedNumberOfFeedItems));

    $this->artisan(FetchFeedItems::class)
        ->assertExitCode(Command::SUCCESS);

    $numberOfFeedItems = $feed->feedItems()->count();

    static::assertSame($expectedNumberOfFeedItems, $numberOfFeedItems);

    $this->artisan(FetchFeedItems::class)
        ->assertExitCode(Command::SUCCESS);

    static::assertSame($numberOfFeedItems, $feed->feedItems()->count());
});

test('does not store older feed items', function () {
    $this->freezeTime();

    $expectedNumberOfFeedItems = 2;

    $user = User::factory()->create();
    $feed = Feed::factory()->state(['feed_url' => 'https://feed.laravel-news.com/'])->recycle($user)->create();

    $dummyRssFeed = getDummyRssFeed($expectedNumberOfFeedItems);
    $dummyRssFeed->setFeedItems($dummyRssFeed->getFeedItems()
        ->merge([
            getDummyRssFeedItem(
                now()
                    ->subMonths(config('app.fetch_articles_not_older_than_months'))
                    ->subDay()
            ),
        ]));

    $heraRssCrawlerMock = $this->partialMock(HeraRssCrawler::class);
    $heraRssCrawlerMock->shouldReceive('parseFeed')->once()->andReturn($dummyRssFeed);

    $this->artisan(FetchFeedItems::class)
        ->assertExitCode(Command::SUCCESS);

    $numberOfFeedItems = $feed->feedItems()->count();

    static::assertSame($expectedNumberOfFeedItems, $numberOfFeedItems);

    $feed->feedItems
        ->each(function (FeedItem $feedItem) {
            static::assertTrue($feedItem->posted_at->gte(today()->subMonths(config('app.fetch_articles_not_older_than_months'))));
        });
});

test('generates blur hash for image', function () {
    $verifiedUser = User::factory()->create();
    $verifiedUserFeed = Feed::factory()->state(['feed_url' => 'https://feed.laravel-news.com/'])->recycle($verifiedUser)->create();

    static::assertSame(0, $verifiedUserFeed->feedItems()->count());

    $dummyRssFeed = getDummyRssFeed(1);
    $dummyRssFeed->setFeedItems(collect([
        getDummyRssFeedItem(
            null,
            'https://placehold.co/600x400/EEE/31343C/png'
        ),
    ]));

    $heraRssCrawlerMock = $this->partialMock(HeraRssCrawler::class);
    $heraRssCrawlerMock->shouldReceive('parseFeed')->once()->andReturn($dummyRssFeed);

    $this->artisan(FetchFeedItems::class)
        ->assertExitCode(Command::SUCCESS);

    static::assertSame('L9R3TW%M-;%M-;j[j[fQ~qj[D%ay', $verifiedUserFeed->feedItems->first()->blur_hash);
});

// Helpers
function __construct(string $name, private int $dummyRssFeedItemId = 1)
{
    parent::__construct($name);
}

/**
     * @param  Collection<RssFeedItem>  $rssFeedItems
     *
     * @throws ReflectionException
     */
function getDummyRssFeed(int $numberOfFeedItems): RssFeed
{
    $rssFeed = new RssFeed;

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
    $rssFeed->setFeedItems(collect(range(1, $numberOfFeedItems))->map(fn () => test()->getDummyRssFeedItem()));
    $rssFeed->setChecksum(HeraRssCrawler::generateChecksumForFeed($rssFeed));

    return $rssFeed;
}

function getDummyRssFeedItem(?Carbon $date = null, ?string $imageUrl = null): RssFeedItem
{
    test()->dummyRssFeedItemId++;

    $rssFeedItem = new RssFeedItem;
    $rssFeedItem->setCategories(collect());
    $rssFeedItem->setAuthors(collect());

    $rssFeedItem->setTitle("Dummy article #{test()->dummyRssFeedItemId}");
    $rssFeedItem->setCommentCount(0);
    $rssFeedItem->setCommentFeedLink('https://test.dev');
    $rssFeedItem->setCommentLink('https://test.dev');
    $rssFeedItem->setContent('Dummy content');
    $rssFeedItem->setCreatedAt($date ?? now());
    $rssFeedItem->setUpdatedAt($date ?? now());
    $rssFeedItem->setDescription('Dummy description');
    $rssFeedItem->setEnclosureUrl("https://test.dev/{test()->dummyRssFeedItemId}");
    $rssFeedItem->setImageUrls($imageUrl ? collect([$imageUrl]) : collect());
    $rssFeedItem->setEncoding('utf-8');
    $rssFeedItem->setId("article-{test()->dummyRssFeedItemId}");
    $rssFeedItem->setLinks(collect());
    $rssFeedItem->setPermalink("https://test.dev/permalink/{test()->dummyRssFeedItemId}");
    $rssFeedItem->setType('Dummy article');
    $rssFeedItem->setXml(null);

    $rssFeedItem->generateChecksum();

    return $rssFeedItem;
}
