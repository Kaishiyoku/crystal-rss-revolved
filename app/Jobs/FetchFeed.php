<?php

namespace App\Jobs;

use App\Models\Feed;
use App\Models\FeedItem;
use Exception;
use ForceUTF8\Encoding;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\Attributes\WithoutRelations;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Kaishiyoku\HeraRssCrawler\HeraRssCrawler;
use Kaishiyoku\HeraRssCrawler\Models\Rss\FeedItem as RssFeedItem;
use Psr\Log\LoggerInterface;

class FetchFeed implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private LoggerInterface $logger;

    /**
     * @var Collection<Collection<int>>
     */
    private Collection $newFeedItemIdsPerUserId;

    /**
     * Create a new job instance.
     */
    public function __construct(
        #[WithoutRelations]
        public Feed $feed
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(HeraRssCrawler $heraRssCrawler): void
    {
        $this->logger = Log::channel('feed_updater');
        $this->newFeedItemIdsPerUserId = collect();

        $this->logger->info("Fetching feed {$this->feed->name}");

        $minFeedDate = today()->subMonths(config('app.fetch_articles_not_older_than_months'));

        try {
            $rssFeed = $heraRssCrawler->parseFeed($this->feed->feed_url);

            $rssFeed->getFeedItems()
                ->filter(fn (RssFeedItem $rssFeedItem) => $rssFeedItem->getCreatedAt()?->gte($minFeedDate))
                ->each(function (RssFeedItem $rssFeedItem) {
                    $this->storeRssFeedItem($rssFeedItem);
                });

            $this->feed->last_failed_at = null;
            if ($this->feed->isDirty()) {
                $this->feed->save();
            }
        } catch (ClientException|Exception $exception) {
            $this->logger->error($exception, [$this->feed->feed_url]);

            $this->feed->last_failed_at = now();
            $this->feed->save();
        }

        $this->feed->last_checked_at = now();

        $this->feed->save();
    }

    private function storeRssFeedItem(RssFeedItem $rssFeedItem): void
    {
        // don't save duplicate items, items without a creation date or items which are older than the prune time
        if (FeedItem::whereChecksum($rssFeedItem->getChecksum())->count() > 0
            || ! $rssFeedItem->getCreatedAt()
            || $rssFeedItem->getCreatedAt()->isBefore(now()->subMonths(config('app.months_after_pruning_feed_items')))) {
            return;
        }

        $imageUrl = $rssFeedItem->getImageUrls()->first() ?? $rssFeedItem->getEnclosureUrl();
        $imageMimetype = $imageUrl ? getContentTypeForUrl($imageUrl) : null;

        $feedItem = new FeedItem([
            'url' => $rssFeedItem->getPermalink(),
            'title' => Str::limit(Encoding::toUTF8($rssFeedItem->getTitle()), 512),
            'image_url' => $imageUrl,
            'image_mimetype' => $imageMimetype,
            'blur_hash' => $imageUrl ? generateBlurHashByUrl($imageUrl) : null,
            'description' => Str::limit(Encoding::toUTF8(strip_tags($rssFeedItem->getDescription())), 1024),
            'posted_at' => $rssFeedItem->getCreatedAt(),
            'checksum' => $rssFeedItem->getChecksum(),
        ]);

        $this->feed->feedItems()->save($feedItem);

        $this->newFeedItemIdsPerUserId->put($this->feed->user_id, with(
            $this->newFeedItemIdsPerUserId->get($this->feed->user_id), fn ($collection
            ) => $collection ? $collection->push($feedItem->id) : collect($feedItem->id) /** @phpstan-ignore-line */
        ));
    }
}
