<?php

use App\Console\Commands\FetchFeedItems;
use App\Models\Feed;
use App\Models\FeedItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Kaishiyoku\HeraRssCrawler\HeraRssCrawler;
use Kaishiyoku\HeraRssCrawler\Models\Rss\Feed as RssFeed;
use Kaishiyoku\HeraRssCrawler\Models\Rss\FeedItem as RssFeedItem;

use function Pest\Laravel\artisan;
use function Pest\Laravel\freezeTime;
use function Pest\Laravel\partialMock;

// Helpers
$getDummyRssFeedItem = function (int $id, ?Carbon $date = null, ?string $imageUrl = null): RssFeedItem {
    $rssFeedItem = new RssFeedItem;
    $rssFeedItem->setCategories(collect());
    $rssFeedItem->setAuthors(collect());

    $rssFeedItem->setTitle("Dummy article #{$id}");
    $rssFeedItem->setCommentCount(0);
    $rssFeedItem->setCommentFeedLink('https://test.dev');
    $rssFeedItem->setCommentLink('https://test.dev');
    $rssFeedItem->setContent('Dummy content');
    $rssFeedItem->setCreatedAt($date ?? now());
    $rssFeedItem->setUpdatedAt($date ?? now());
    $rssFeedItem->setDescription('Dummy description');
    $rssFeedItem->setEnclosureUrl("https://test.dev/{$id}");
    $rssFeedItem->setImageUrls($imageUrl ? collect([$imageUrl]) : collect());
    $rssFeedItem->setEncoding('utf-8');
    $rssFeedItem->setId("article-{$id}");
    $rssFeedItem->setLinks(collect());
    $rssFeedItem->setPermalink("https://test.dev/permalink/{$id}");
    $rssFeedItem->setType('Dummy article');
    $rssFeedItem->setXml(null);

    $rssFeedItem->generateChecksum();

    return $rssFeedItem;
};

/**
 * @param  int  $numberOfFeedItems
 * @return RssFeed
 *
 * @throws ReflectionException
 */
$getDummyRssFeed = function (int $numberOfFeedItems) use ($getDummyRssFeedItem): RssFeed {
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
    $rssFeed->setFeedItems(collect(range(1, $numberOfFeedItems))->map(fn (int $id) => $getDummyRssFeedItem($id)));
    $rssFeed->setChecksum(HeraRssCrawler::generateChecksumForFeed($rssFeed));

    return $rssFeed;
};

uses(RefreshDatabase::class);

test('fetch feed items only for verified users', function () use ($getDummyRssFeed) {
    $expectedNumberOfFeedItems = 2;

    $unverifiedUser = User::factory()->unverified()->create();
    $unverifiedUserFeed = Feed::factory()->state(['feed_url' => 'https://feed.laravel-news.com/'])->recycle($unverifiedUser)->create();

    $verifiedUser = User::factory()->create();
    $verifiedUserFeed = Feed::factory()->state(['feed_url' => 'https://feed.laravel-news.com/'])->recycle($verifiedUser)->create();

    $heraRssCrawlerMock = partialMock(HeraRssCrawler::class);
    $heraRssCrawlerMock->shouldReceive('parseFeed')->once()->andReturn($getDummyRssFeed($expectedNumberOfFeedItems));

    artisan(FetchFeedItems::class)
        ->assertExitCode(Command::SUCCESS);

    expect($unverifiedUserFeed->feedItems()->count())->toBe(0)
        ->and($verifiedUserFeed->feedItems()->count())->toBe($expectedNumberOfFeedItems);
});

test('invalid rss feed', function () {
    $user = User::factory()
        ->has(Feed::factory()->state(['feed_url' => 'https://laravel-news.com']))
        ->create();

    $heraRssCrawlerMock = partialMock(HeraRssCrawler::class);
    $heraRssCrawlerMock->shouldReceive('parseFeed')->once()->andThrow(new RuntimeException);

    artisan(FetchFeedItems::class)
        ->assertExitCode(Command::SUCCESS);

    expect($user->feedItems()->count())->toBe(0)
        ->and($user->feeds()->first()->last_failed_at)->not->toBeNull()
        ->and($user->feeds()->first()->last_checked_at)->not->toBeNull();
});

test('does not save duplicate feed items', function () use ($getDummyRssFeed) {
    $expectedNumberOfFeedItems = 2;

    $user = User::factory()->create();
    $feed = Feed::factory()->state(['feed_url' => 'https://feed.laravel-news.com/'])->recycle($user)->create();

    $heraRssCrawlerMock = partialMock(HeraRssCrawler::class);
    $heraRssCrawlerMock->shouldReceive('parseFeed')->twice()->andReturn($getDummyRssFeed($expectedNumberOfFeedItems));

    artisan(FetchFeedItems::class)
        ->assertExitCode(Command::SUCCESS);

    $numberOfFeedItems = $feed->feedItems()->count();

    expect($numberOfFeedItems)->toBe($expectedNumberOfFeedItems);

    artisan(FetchFeedItems::class)
        ->assertExitCode(Command::SUCCESS);

    expect($feed->feedItems()->count())->toBe($numberOfFeedItems);
});

test('does not store older feed items', function () use ($getDummyRssFeed, $getDummyRssFeedItem) {
    freezeTime();

    $expectedNumberOfFeedItems = 2;

    $user = User::factory()->create();
    $feed = Feed::factory()->state(['feed_url' => 'https://feed.laravel-news.com/'])->recycle($user)->create();

    $dummyRssFeed = $getDummyRssFeed($expectedNumberOfFeedItems);
    $dummyRssFeed->setFeedItems($dummyRssFeed->getFeedItems()
        ->merge([
            $getDummyRssFeedItem(
                1,
                now()
                    ->subMonths(config('app.fetch_articles_not_older_than_months'))
                    ->subDay()
            ),
        ]));

    $heraRssCrawlerMock = partialMock(HeraRssCrawler::class);
    $heraRssCrawlerMock->shouldReceive('parseFeed')->once()->andReturn($dummyRssFeed);

    artisan(FetchFeedItems::class)
        ->assertExitCode(Command::SUCCESS);

    $numberOfFeedItems = $feed->feedItems()->count();

    expect($numberOfFeedItems)->toBe($expectedNumberOfFeedItems);

    $feed->feedItems
        ->each(function (FeedItem $feedItem) {
            expect($feedItem->posted_at->gte(today()->subMonths(config('app.fetch_articles_not_older_than_months'))))->toBeTrue();
        });
});

test('generates blur hash for image', function () use ($getDummyRssFeed, $getDummyRssFeedItem) {
    $verifiedUser = User::factory()->create();
    $verifiedUserFeed = Feed::factory()->state(['feed_url' => 'https://feed.laravel-news.com/'])->recycle($verifiedUser)->create();

    expect($verifiedUserFeed->feedItems()->count())->toBe(0);

    $dummyRssFeed = $getDummyRssFeed(1);
    $dummyRssFeed->setFeedItems(collect([
        $getDummyRssFeedItem(
            1,
            null,
            'https://placehold.co/600x400/EEE/31343C/png'
        ),
    ]));

    $heraRssCrawlerMock = partialMock(HeraRssCrawler::class);
    $heraRssCrawlerMock->shouldReceive('parseFeed')->once()->andReturn($dummyRssFeed);

    artisan(FetchFeedItems::class)
        ->assertExitCode(Command::SUCCESS);

    expect($verifiedUserFeed->feedItems->first()->blur_hash)->toBe('L9R3TW%M-;%M-;j[j[fQ~qj[D%ay');
});
