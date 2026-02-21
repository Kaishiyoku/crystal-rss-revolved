<?php

use App\Models\Feed;
use App\Models\FeedItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\freezeTime;
use function Pest\Laravel\put;

uses(RefreshDatabase::class);

test('marks feed item as unread', function () {
    freezeTime();

    actingAs($user = User::factory()->create());

    $feed = Feed::factory()->recycle($user)->create();
    $feedItem = FeedItem::factory()->recycle($feed)->state(['read_at' => now()])->create();

    put(route('mark-feed-item-as-unread', $feedItem))
        ->assertOk()
        ->assertJsonPath('read_at', null);
});

test('marks feed item as unread again', function () {
    freezeTime();

    actingAs($user = User::factory()->create());

    $feed = Feed::factory()->recycle($user)->create();
    $feedItem = FeedItem::factory()->recycle($feed)->state(['read_at' => now()])->create();

    put(route('mark-feed-item-as-unread', $feedItem))
        ->assertOk()
        ->assertJsonPath('read_at', null);

    put(route('mark-feed-item-as-unread', $feedItem))
        ->assertOk()
        ->assertJsonPath('read_at', null);
});

test('cannot mark feed item of another user as unread', function () {
    actingAs(User::factory()->create());

    $feed = Feed::factory()->create();
    $feedItem = FeedItem::factory()->recycle($feed)->create();

    put(route('mark-feed-item-as-unread', $feedItem))
        ->assertForbidden();
});
