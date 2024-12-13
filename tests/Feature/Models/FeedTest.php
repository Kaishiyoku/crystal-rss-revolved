<?php

use App\Models\Category;
use App\Models\Feed;
use App\Models\FeedItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('feed belongs to user', function () {
    $user = User::factory()->create();
    $feed = Feed::factory()->for($user)->create();

    expect($feed->user->id)->toBe($user->id);
});

test('feed belongs to category', function () {
    $category = Category::factory()->create();
    $feed = Feed::factory()->for($category)->create();

    expect($feed->category->id)->toBe($category->id);
});

test('feed has feed items', function () {
    $feed = Feed::factory()->create();
    $feedItems = FeedItem::factory(5)->for($feed)->create();

    expect($feed->feedItems()->pluck('id'))->toEqual($feedItems->sortByDesc('posted_at')->pluck('id'));
});
