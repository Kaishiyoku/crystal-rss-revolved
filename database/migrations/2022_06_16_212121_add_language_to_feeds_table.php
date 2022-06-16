<?php

use App\Models\Feed;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Kaishiyoku\HeraRssCrawler\HeraRssCrawler;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feeds', function (Blueprint $table) {
            $table->string('language')->nullable()->after('name');
        });

        // migrate existing feeds

        $heraRssCrawler = new HeraRssCrawler();
        $heraRssCrawler->setRetryCount(config('app.rss_crawler_retry_count'));

        Feed::all()->each(function (Feed $feed) use ($heraRssCrawler) {
            try {
                $rssFeed = $heraRssCrawler->parseFeed($feed->feed_url);

                if (!$rssFeed) {
                    return;
                }

                $feed->update([
                    'language' =>$rssFeed->getLanguage(),
                ]);
            } catch (ClientException | Exception $e) {
                Log::error($e, [$feed->feed_url]);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feeds', function (Blueprint $table) {
            $table->dropColumn('language');
        });
    }
};
