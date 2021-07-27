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
use Illuminate\Support\Facades\Log;
use Kaishiyoku\HeraRssCrawler\HeraRssCrawler;
use Kaishiyoku\HeraRssCrawler\Models\Rss\FeedItem as RssFeedItem;
use Str;

class FetchFeedItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feed:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch feed items';

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var HeraRssCrawler
     */
    private $heraRssCrawler;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->logger = Log::channel('feed_updater');
        $this->heraRssCrawler = new HeraRssCrawler();
        $this->heraRssCrawler->setLogger($this->logger);
        $this->heraRssCrawler->setRetryCount(config('app.rss_crawler_retry_count'));
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $startTime = microtime(true);

        User::verified()->with('feeds')->each(function (User $user) {
            $this->logger->info("Fetching feeds for user {$user->name}");

            $this->fetchFeedsForUser($user);
        });

        $executionTimeInSeconds = round(microtime(true) - $startTime);

        $this->logger->info("Duration: {$executionTimeInSeconds}s");

        return 0;
    }

    /**
     * @param User $user
     */
    private function fetchFeedsForUser(User $user)
    {
        $user->feeds->each(function (Feed $feed) use ($user) {
            $this->fetchFeed($feed);
        });
    }

    /**
     * @param Feed $feed
     */
    private function fetchFeed(Feed $feed)
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
        } catch (ClientException $e) {
            $this->logger->error($e, [$feed->feed_url]);
        } catch (Exception $e) {
            $this->logger->error($e, [$feed->feed_url]);
        }

        $feed->last_checked_at = now();

        $feed->save();
    }

    /**
     * @param Feed $feed
     * @param RssFeedItem $rssFeedItem
     */
    private function storeRssFeedItem(Feed $feed, RssFeedItem $rssFeedItem)
    {
        // don't save duplicate items
        if (FeedItem::whereChecksum($rssFeedItem->getChecksum())->count() > 0 || !$rssFeedItem->getCreatedAt()) {
            return;
        }

        $imageUrl = Arr::first($rssFeedItem->getImageUrls()) ?? $rssFeedItem->getEnclosureUrl();
        $imageMimetype = $imageUrl ? getContentTypeForUrl($imageUrl) : null;

        $feedItem = FeedItem::make([
            'url' => $rssFeedItem->getPermalink(),
            'title' => Encoding::toUTF8($rssFeedItem->getTitle()),
            'image_url' => $imageUrl,
            'image_mimetype' => $imageMimetype,
            'description' => Str::limit(Encoding::toUTF8(strip_tags($rssFeedItem->getDescription())), 1024),
            'posted_at' => $rssFeedItem->getCreatedAt(),
            'checksum' => $rssFeedItem->getChecksum(),
        ]);

        $feed->feedItems()->save($feedItem);
    }
}
