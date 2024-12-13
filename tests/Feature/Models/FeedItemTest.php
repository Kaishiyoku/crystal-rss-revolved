<?php

use App\Models\Feed;
use App\Models\FeedItem;
use Illuminate\Console\Command;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;

use function Pest\Laravel\artisan;
use function Pest\Laravel\freezeTime;

uses(RefreshDatabase::class);
uses(WithFaker::class);

test('feed item belongs to feed', function () {
    $feed = Feed::factory()->create();
    $feedItem = FeedItem::factory()->for($feed)->create();

    expect($feedItem->feed->id)->toBe($feed->id);
});

test('unread scope', function () {
    $unreadFeedItemIds = FeedItem::factory(5)->state(['read_at' => null])->create()->pluck('id');
    FeedItem::factory(5)->state(['read_at' => now()])->create()->pluck('id');

    expect(FeedItem::unread()->pluck('id'))->toEqual($unreadFeedItemIds);
});

test('of feed scope', function () {
    $feedA = Feed::factory()->create();
    $feedItemIdsOfFeedA = FeedItem::factory(5)->for($feedA)->create()->pluck('id');

    $feedB = Feed::factory()->create();
    $feedItemIdsOfFeedB = FeedItem::factory(5)->for($feedB)->create()->pluck('id');

    expect(FeedItem::ofFeed($feedA->id)->pluck('id')->sort()->values())->toEqual($feedItemIdsOfFeedA->sort()->values())
        ->and(FeedItem::ofFeed($feedB->id)->pluck('id')->sort()->values())->toEqual($feedItemIdsOfFeedB->sort()->values())
        ->and(FeedItem::ofFeed(null)->pluck('id')->sort()->values())->toEqual($feedItemIdsOfFeedA->merge($feedItemIdsOfFeedB)->sort()->values());
});

test('has image attribute', function () {
    $feedItemWithImage = FeedItem::factory()
        ->state([
            'image_url' => fake()->imageUrl(),
            'image_mimetype' => fake()->randomElement(['image/png', 'image/jpeg']),
        ])
        ->create();

    $feedItemWithoutImage = FeedItem::factory()
        ->state([
            'image_url' => null,
            'image_mimetype' => null,
        ])
        ->create();

    $feedItemWithInvalidImageMimetype = FeedItem::factory()
        ->state([
            'image_url' => fake()->imageUrl(),
            'image_mimetype' => 'text/plain',
        ])
        ->create();

    $feedItems = FeedItem::factory(20)->create();

    expect($feedItemWithImage->has_image)->toBeTrue()
        ->and($feedItemWithoutImage->has_image)->toBeFalse()
        ->and($feedItemWithInvalidImageMimetype->has_image)->toBeFalse();

    $feedItems->each(function (FeedItem $feedItem) {
        expect($feedItem->has_image)->toBe((bool) $feedItem->image_url);
    });
});

test('per page', function () {
    $expectedPerPage = 20;
    $feedItem = FeedItem::factory()->create();

    Config::set('app.feed_items_per_page', $expectedPerPage);

    expect($feedItem->getPerPage())->toBe($expectedPerPage);
});

test('prunable', function () {
    freezeTime();

    $readPrunableFeedItemIds = FeedItem::factory(10)
        ->state(
            [
                'read_at' => now()->subMonths(5),
                'created_at' => now()->subMonths(5),
            ]
        )
        ->create()
        ->pluck('id');
    $unreadPrunableFeedItemIds = FeedItem::factory(10)
        ->state(
            [
                'read_at' => null,
                'created_at' => now()->subMonths(5),
            ]
        )
        ->create()
        ->pluck('id');
    $readNotPrunableFeedItemIds = FeedItem::factory(10)
        ->state(
            [
                'read_at' => now()->subMonths(5)->addSecond(),
                'created_at' => now()->subMonths(5)->addSecond(),
            ]
        )
        ->create()
        ->pluck('id');
    $unreadNotPrunableFeedItemIds = FeedItem::factory(10)
        ->state(
            [
                'read_at' => null,
                'created_at' => now(5)->subMonths(5)->addSecond(),
            ]
        )
        ->create()
        ->pluck('id');

    Config::set('app.months_after_pruning_feed_items', 5);

    expect((new FeedItem)->prunable()->pluck('id'))->toEqual($readPrunableFeedItemIds->merge($unreadPrunableFeedItemIds)->values()->sort())
        ->and((new FeedItem)->prunable()->pluck('id'))->not->toContain($readNotPrunableFeedItemIds->merge($unreadNotPrunableFeedItemIds)->values()->sort());

    artisan('model:prune')
        ->assertExitCode(Command::SUCCESS);

    expect(FeedItem::orderBy('id')->pluck('id'))
        ->toEqual($readNotPrunableFeedItemIds->merge($unreadNotPrunableFeedItemIds)->values()->sort());
});
