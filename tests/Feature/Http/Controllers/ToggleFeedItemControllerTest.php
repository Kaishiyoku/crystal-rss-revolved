<?php

use App\Models\Feed;
use App\Models\FeedItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\freezeTime;
use function Pest\Laravel\put;

uses(RefreshDatabase::class);

test('marks feed item as read', function () {
    freezeTime();

    actingAs($user = User::factory()->create());

    $feed = Feed::factory()->recycle($user)->create();
    $feedItem = FeedItem::factory()->recycle($feed)->state(['read_at' => null])->create();

    put(route('toggle-feed-item', $feedItem))
        ->assertOk()
        ->assertJsonPath('read_at', now()->micro(0)->toJSON());
});

test('marks feed item as unread', function () {
    freezeTime();

    actingAs($user = User::factory()->create());

    $feed = Feed::factory()->recycle($user)->create();
    $feedItem = FeedItem::factory()->recycle($feed)->state(['read_at' => now()])->create();

    put(route('toggle-feed-item', $feedItem))
        ->assertOk()
        ->assertJsonPath('read_at', null);
});

test('cannot toggle feed item of another user', function () {
    actingAs(User::factory()->create());

    $feed = Feed::factory()->create();
    $feedItem = FeedItem::factory()->recycle($feed)->create();

    put(route('toggle-feed-item', $feedItem))
        ->assertForbidden();
});
