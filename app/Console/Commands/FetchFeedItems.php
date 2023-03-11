<?php

namespace App\Console\Commands;

use App\Models\Feed;
use App\Models\FeedItem;
use App\Models\User;
use Exception;
use ForceUTF8\Encoding;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Kaishiyoku\HeraRssCrawler\HeraRssCrawler;
use Laravel\Telescope\Telescope;
use Psr\Log\LoggerInterface;
use Kaishiyoku\HeraRssCrawler\Models\Rss\FeedItem as RssFeedItem;

class FetchFeedItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-feed-items';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch new feed items';

    private LoggerInterface $logger;

    private HeraRssCrawler $heraRssCrawler;

    /**
     * @var Collection<Collection<int>>
     */
    private Collection $newFeedItemIdsPerUserId;

    public function __construct()
    {
        parent::__construct();

        $this->logger = Log::channel('feed_updater');
        $this->heraRssCrawler = new HeraRssCrawler();
        $this->heraRssCrawler->setLogger($this->logger);
        $this->heraRssCrawler->setRetryCount(config('app.rss_crawler_retry_count'));
        $this->newFeedItemIdsPerUserId = collect();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $startTime = microtime(true);

        User::verified()->with('feeds')->get()->each(function (User $user) {
            $this->logger->info("Fetching feeds for user {$user->name}");

            $this->fetchFeedsForUser($user);
        });

        $this->newFeedItemIdsPerUserId
            ->filter(fn($feedItemIds) => $feedItemIds->isNotEmpty())
            ->each(function ($feedItemIds, $userId) {
                $this->logger->info("Number of new feed items for user #{$userId}: {$feedItemIds->count()}");
            });

        $executionTimeInSeconds = round(microtime(true) - $startTime);

        $this->logger->info("Duration: {$executionTimeInSeconds}s");
    }

    private function fetchFeedsForUser(User $user): void
    {
        $user->feeds->each(function (Feed $feed) {
            $this->fetchFeed($feed);
        });
    }

    private function fetchFeed(Feed $feed): void
    {
        $this->logger->info("Fetching feed {$feed->name}");

        try {
            $rssFeed = $this->heraRssCrawler->parseFeed($feed->feed_url);

            if (!$rssFeed) {
                return;
            }

            $rssFeed->getFeedItems()->each(function (RssFeedItem $rssFeedItem) use ($feed) {
                $this->storeRssFeedItem($feed, $rssFeedItem);
            });
        } catch (ClientException | Exception $exception) {
            $this->logger->error($exception, [$feed->feed_url]);
            Telescope::catch($exception, ['feed-updater', $feed->feed_url]);
        }

        $feed->last_checked_at = now();

        $feed->save();
    }

    private function storeRssFeedItem(Feed $feed, RssFeedItem $rssFeedItem): void
    {
        // don't save duplicate items, items without a creation date or items which are older than the prune time
        if (FeedItem::whereChecksum($rssFeedItem->getChecksum())->count() > 0
            || !$rssFeedItem->getCreatedAt()
            || $rssFeedItem->getCreatedAt()->isBefore(now()->subMonths(config('app.months_after_pruning_feed_items')))) {
            return;
        }

        $imageUrl = Arr::first($rssFeedItem->getImageUrls()) ?? $rssFeedItem->getEnclosureUrl();
        $imageMimetype = $imageUrl ? getContentTypeForUrl($imageUrl) : null;

        $feedItem = new FeedItem([
            'url' => $rssFeedItem->getPermalink(),
            'title' => Encoding::toUTF8($rssFeedItem->getTitle()),
            'image_url' => $imageUrl,
            'image_mimetype' => $imageMimetype,
            'description' => Str::limit(Encoding::toUTF8(strip_tags($rssFeedItem->getDescription())), 1024),
            'posted_at' => $rssFeedItem->getCreatedAt(),
            'checksum' => $rssFeedItem->getChecksum(),
        ]);

        $feed->feedItems()->save($feedItem);

        $this->newFeedItemIdsPerUserId->put($feed->user_id, with(
            $this->newFeedItemIdsPerUserId->get($feed->user_id), fn($collection) => $collection ? $collection->push($feedItem->id) : collect($feedItem->id)
        ));
    }
}
