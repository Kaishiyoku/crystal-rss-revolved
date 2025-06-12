<?php

use App\Models\Category;
use App\Models\Feed;
use App\Models\FeedItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('has traits', function () {
    expect((new ReflectionClass(Feed::class))->getTraitNames())->toBe([
        HasFactory::class,
    ]);
});

it('has fillable attributes', function () {
    expect((new ReflectionProperty(Feed::factory()->create(), 'fillable'))->getDefaultValue())
        ->toBe([
            'feed_url',
            'site_url',
            'favicon_url',
            'name',
            'language',
            'is_purgeable',
            'is_pdf_export_enabled',
        ]);
});

it('casts attributes', function () {
    $feed = Feed::factory()->create();

    expect((new ReflectionMethod($feed, 'casts'))->invoke($feed))
        ->toBe([
            'is_purgeable' => 'bool',
            'is_pdf_export_enabled' => 'bool',
            'last_checked_at' => 'datetime',
            'last_failed_at' => 'datetime',
        ]);
});

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
