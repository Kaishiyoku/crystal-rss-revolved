<?php

use App\Models\Feed;
use App\Models\FeedItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\freezeTime;
use function Pest\Laravel\put;
use function Pest\Laravel\travelTo;

uses(RefreshDatabase::class);

test('marks feed item as read', function () {
    freezeTime();

    actingAs($user = User::factory()->create());

    $feed = Feed::factory()->recycle($user)->create();
    $feedItem = FeedItem::factory()->recycle($feed)->state(['read_at' => null])->create();

    put(route('mark-feed-item-as-read', $feedItem))
        ->assertOk()
        ->assertJsonPath('read_at', now()->micro(0)->toJSON());
});

test('marks feed item as read again', function () {
    freezeTime();
    travelTo('2026-02-20 10:00:00');

    actingAs($user = User::factory()->create());

    $feed = Feed::factory()->recycle($user)->create();
    $feedItem = FeedItem::factory()->recycle($feed)->state(['read_at' => null])->create();

    put(route('mark-feed-item-as-read', $feedItem))
        ->assertOk()
        ->assertJsonPath('read_at', '2026-02-20T10:00:00.000000Z');

    travelTo('2026-02-20 12:00:00');

    put(route('mark-feed-item-as-read', $feedItem))
        ->assertOk()
        ->assertJsonPath('read_at', '2026-02-20T12:00:00.000000Z');
});

test('cannot mark feed item of another user as read', function () {
    actingAs(User::factory()->create());

    $feed = Feed::factory()->create();
    $feedItem = FeedItem::factory()->recycle($feed)->create();

    put(route('mark-feed-item-as-read', $feedItem))
        ->assertForbidden();
});
