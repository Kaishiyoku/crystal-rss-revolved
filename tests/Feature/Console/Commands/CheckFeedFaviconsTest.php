<?php

use App\Console\Commands\CheckFeedFavicons;
use App\Models\Feed;
use Illuminate\Console\Command;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Kaishiyoku\HeraRssCrawler\HeraRssCrawler;
use Tests\TestCase;

uses(RefreshDatabase::class);

test('update feed favicon urls', function () {
    $invalidFaviconUrl = 'filled_but_will_change';
    $validFaviconUrl = 'https://laravel-news.com/apple-touch-icon.png';

    $feedWithoutFavicon = Feed::factory()->create([
        'site_url' => 'https://laravel-news.com/',
        'favicon_url' => null,
    ]);
    $feedWithChangingFavicon = Feed::factory()->create([
        'site_url' => 'https://laravel-news.com/',
        'favicon_url' => $invalidFaviconUrl,
    ]);
    $feedWithoutChangingFavicon = Feed::factory()->create([
        'site_url' => 'https://laravel-news.com/',
        'favicon_url' => $validFaviconUrl,
    ]);

    $heraRssCrawlerMock = $this->partialMock(HeraRssCrawler::class);
    $heraRssCrawlerMock->shouldReceive('discoverFavicon')->times(3)->andReturn($validFaviconUrl);

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
});
