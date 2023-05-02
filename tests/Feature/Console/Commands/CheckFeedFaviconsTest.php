<?php

namespace Tests\Feature\Console\Commands;

use App\Console\Commands\CheckFeedFavicons;
use App\Models\Feed;
use Illuminate\Console\Command;
use Tests\TestCase;

class CheckFeedFaviconsTest extends TestCase
{
    public function test_update_feed_favicon_urls(): void
    {
        $invalidFaviconUrl = 'filled_but_will_change';
        $validFaviconUrl = 'https://petapixel.com/wp-content/themes/petapixel-2017/assets/prod/img/favicon.ico';

        $feedWithoutFavicon = Feed::factory()->create([
            'site_url' => 'https://petapixel.com/',
            'favicon_url' => null,
        ]);
        $feedWithChangingFavicon = Feed::factory()->create([
            'site_url' => 'https://petapixel.com/',
            'favicon_url' => $invalidFaviconUrl,
        ]);
        $feedWithoutChangingFavicon = Feed::factory()->create([
            'site_url' => 'https://petapixel.com/',
            'favicon_url' => $validFaviconUrl,
        ]);

        $this->artisan(CheckFeedFavicons::class)
            ->assertExitCode(Command::SUCCESS);

        $feedWithoutFavicon->refresh();
        $feedWithChangingFavicon->refresh();
        $feedWithoutChangingFavicon->refresh();

        static::assertNotNull($feedWithoutFavicon->favicon_url);
        static::assertNotNull($feedWithChangingFavicon->favicon_url);
        static::assertNotNull($feedWithoutChangingFavicon->favicon_url);

        static::assertNotSame($invalidFaviconUrl, $feedWithChangingFavicon->favicon_url);
        static::assertSame($validFaviconUrl, $feedWithChangingFavicon->favicon_url);
        static::assertSame($feedWithoutChangingFavicon->created_at->timestamp, $feedWithoutChangingFavicon->updated_at->timestamp);
    }
}
