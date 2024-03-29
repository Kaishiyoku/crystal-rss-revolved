<?php

namespace App\Console\Commands;

use App\Jobs\FetchFeed;
use App\Models\Feed;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;

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

    /**
     * @var Collection<Collection<int>>
     */
    private Collection $newFeedItemIdsPerUserId;

    public function __construct()
    {
        parent::__construct();

        $this->logger = Log::channel('feed_updater');
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
            ->filter(fn ($feedItemIds) => $feedItemIds->isNotEmpty())
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
        FetchFeed::dispatch($feed);
    }
}
