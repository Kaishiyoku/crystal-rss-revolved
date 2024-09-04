<?php

namespace App\Console\Commands;

use App\Models\Feed;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
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
    protected $signature = 'app:check-feed-favicons';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for changed feed favicons and update them';

    private LoggerInterface $logger;

    private HeraRssCrawler $heraRssCrawler;

    public function __construct(HeraRssCrawler $heraRssCrawler)
    {
        parent::__construct();

        $this->logger = Log::channel('feed_updater');

        $heraRssCrawler->setLogger($this->logger);
        $heraRssCrawler->setRetryCount(config('app.rss_crawler_retry_count'));

        $this->heraRssCrawler = $heraRssCrawler;
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Feed::all()->each(function (Feed $feed) {
            try {
                $feed->fill([
                    'favicon_url' => $this->heraRssCrawler->discoverFavicon($feed->site_url),
                ]);

                // only save if the favicon has been changed
                if ($feed->isClean()) {
                    $this->logger->info("No favicon update needed for feed #{$feed->id}: {$feed->name}");

                    return;
                }

                $feed->save();

                $this->logger->info("Updated favicon for feed #{$feed->id}: {$feed->name}");
            } catch (ClientException|ServerException $exception) { // @codeCoverageIgnoreStart
                $this->logger->warning("Couldn't check favicon for feed #{$feed->id}: {$feed->name}; error message: {$exception->getMessage()}");
            } // @codeCoverageIgnoreEnd
        });
    }
}
