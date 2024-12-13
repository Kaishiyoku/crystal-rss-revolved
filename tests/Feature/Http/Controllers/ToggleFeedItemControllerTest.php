<?php

use App\Models\Feed;
use App\Models\FeedItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class);
uses(RefreshDatabase::class);

test('marks feed item as read', function () {
    $this->freezeTime();

    $this->actingAs($user = User::factory()->create());

    $feed = Feed::factory()->recycle($user)->create();
    $feedItem = FeedItem::factory()->recycle($feed)->state(['read_at' => null])->create();

    $this->put(route('toggle-feed-item', $feedItem))
        ->assertOk()
        ->assertJsonPath('read_at', now()->micro(0)->toJSON());
});

test('marks feed item as unread', function () {
    $this->freezeTime();

    $this->actingAs($user = User::factory()->create());

    $feed = Feed::factory()->recycle($user)->create();
    $feedItem = FeedItem::factory()->recycle($feed)->state(['read_at' => now()])->create();

    $this->put(route('toggle-feed-item', $feedItem))
        ->assertOk()
        ->assertJsonPath('read_at', null);
});

test('cannot toggle feed item of another user', function () {
    $this->actingAs(User::factory()->create());

    $feed = Feed::factory()->create();
    $feedItem = FeedItem::factory()->recycle($feed)->create();

    $this->put(route('toggle-feed-item', $feedItem))
        ->assertForbidden();
});
