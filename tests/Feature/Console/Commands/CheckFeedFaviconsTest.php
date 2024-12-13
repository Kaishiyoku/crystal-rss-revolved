<?php

use App\Console\Commands\CheckFeedFavicons;
use App\Models\Feed;
use Illuminate\Console\Command;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Kaishiyoku\HeraRssCrawler\HeraRssCrawler;

use function Pest\Laravel\artisan;
use function Pest\Laravel\partialMock;

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

    $heraRssCrawlerMock = partialMock(HeraRssCrawler::class);
    $heraRssCrawlerMock->shouldReceive('discoverFavicon')->times(3)->andReturn($validFaviconUrl);

    artisan(CheckFeedFavicons::class)
        ->assertExitCode(Command::SUCCESS);

    $feedWithoutFavicon->refresh();
    $feedWithChangingFavicon->refresh();
    $feedWithoutChangingFavicon->refresh();

    expect($feedWithoutFavicon->favicon_url)->not->toBeNull()
        ->and($feedWithChangingFavicon->favicon_url)->not->toBeNull()
        ->and($feedWithoutChangingFavicon->favicon_url)->not->toBeNull()
        ->and($feedWithChangingFavicon->favicon_url)->not->toBe($invalidFaviconUrl)
        ->and($feedWithChangingFavicon->favicon_url)->toBe($validFaviconUrl)
        ->and($feedWithoutChangingFavicon->updated_at->timestamp)->toBe($feedWithoutChangingFavicon->created_at->timestamp);
});
