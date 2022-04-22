<?php

namespace App\Console\Commands;

use App\Models\Feed;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Kaishiyoku\HeraRssCrawler\HeraRssCrawler;
use Psr\Log\LoggerInterface;

class CheckFeedFavicons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feed:favicons';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for changed favicons';

    private LoggerInterface $logger;

    private HeraRssCrawler $heraRssCrawler;

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
        Feed::all()->each(function (Feed $feed) {
            $feed->fill([
                'favicon_url' => $this->heraRssCrawler->discoverFavicon($feed->site_url),
            ]);

            // only save if the favicon has changed
            if ($feed->isDirty()) {
                $feed->save();

                $this->logger->info("Updated favicon for feed #{$feed->id}: {$feed->name}");
            } else {
                $this->logger->info("No favicon update needed for feed #{$feed->id}: {$feed->name}");
            }
        });

        return 0;
    }
}
