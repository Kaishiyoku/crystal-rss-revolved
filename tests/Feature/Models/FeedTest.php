<?php

use App\Models\Category;
use App\Models\Feed;
use App\Models\FeedItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class);
uses(RefreshDatabase::class);

test('feed belongs to user', function () {
    $user = User::factory()->create();
    $feed = Feed::factory()->for($user)->create();

    static::assertSame($user->id, $feed->user->id);
});

test('feed belongs to category', function () {
    $category = Category::factory()->create();
    $feed = Feed::factory()->for($category)->create();

    static::assertSame($category->id, $feed->category->id);
});

test('feed has feed items', function () {
    $feed = Feed::factory()->create();
    $feedItems = FeedItem::factory(5)->for($feed)->create();

    static::assertEquals(
        $feedItems->sortByDesc('posted_at')->pluck('id'),
        $feed->feedItems()->pluck('id'),
    );
});
