<?php

use App\Models\Feed;
use App\Models\FeedItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

test('index', function () {
    actingAs($user = User::factory()->create());

    $feedWithUnreadFeedItems = Feed::factory()->for($user)->has(FeedItem::factory()->unread()->count(15))->create();
    Feed::factory()->for($user)->has(FeedItem::factory()->unread()->count(5))->create();
    $feedWithoutUnreadFeedItems = Feed::factory()->for($user)->has(FeedItem::factory()->read()->count(3))->create();

    // visit dashboard without any query parameters
    get(route('dashboard'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Dashboard')
            ->where('totalNumberOfFeedItems', 20)
            ->has('feedItems', fn (Assert $page) => $page
                ->count('data', 15)
                ->has('data')
                ->has('path')
                ->has('per_page')
                ->has('next_cursor')
                ->has('next_page_url')
                ->has('prev_cursor')
                ->has('prev_page_url')
            )
            ->where('currentCursor', null)
        );

    // visit dashboard with cursor query parameter
    $feedItems = Auth::user()->feedItems()
        ->unread()
        ->cursorPaginate()
        ->withQueryString();

    $cursor = Arr::get($feedItems->toArray(), 'next_cursor');

    get(route('dashboard').'?'.Arr::query(['cursor' => $cursor]))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Dashboard')
            ->where('totalNumberOfFeedItems', 20)
            ->has('feedItems', fn (Assert $page) => $page
                ->count('data', 5)
                ->has('data')
                ->has('path')
                ->has('per_page')
                ->has('next_cursor')
                ->has('next_page_url')
                ->has('prev_cursor')
                ->has('prev_page_url')
            )
            ->where('currentCursor', $cursor)
        );

    // visit dashboard with selected feed
    get(route('dashboard').'?'.Arr::query(['feed_id' => $feedWithUnreadFeedItems->id]))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Dashboard')
            ->where('totalNumberOfFeedItems', 20)
            ->has('feedItems', fn (Assert $page) => $page
                ->count('data', 15)
                ->has('data')
                ->has('path')
                ->has('per_page')
                ->has('next_cursor')
                ->has('next_page_url')
                ->has('prev_cursor')
                ->has('prev_page_url')
            )
            ->where('currentCursor', null)
        );

    // visit dashboard with selected feed which has no unread feed items
    get(route('dashboard').'?'.Arr::query(['feed_id' => $feedWithoutUnreadFeedItems->id]))
        ->assertRedirect(route('dashboard'));
});

test('cannot access as guest', function () {
    get(route('dashboard'))
        ->assertRedirect('/login');
});
