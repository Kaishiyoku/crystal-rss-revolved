<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Kaishiyoku\HeraRssCrawler\HeraRssCrawler;

class MigrateFaviconUrlAtFeedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $heraRssCrawler = new HeraRssCrawler();

        DB::table('feeds')->get()->each(function ($feed) use ($heraRssCrawler) {
            try {
                $faviconUrl = $heraRssCrawler->discoverFavicon($feed->site_url);

                DB::table('feeds')->where('id', $feed->id)->update(['favicon_url' => $faviconUrl]);
            } catch (Exception $e) {
                Log::warning("Couldn't discover favicon: {$e->getMessage()}");
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
        //
    }
}
