<?php

namespace Http\Controllers;

use App\Models\Feed;
use App\Models\FeedItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index(): void
    {
        $this->actingAs($user = User::factory()->create());

        $feedWithUnreadFeedItems = Feed::factory()->for($user)->has(FeedItem::factory()->unread()->count(15))->create();
        Feed::factory()->for($user)->has(FeedItem::factory()->unread()->count(5))->create();
        $feedWithoutUnreadFeedItems = Feed::factory()->for($user)->has(FeedItem::factory()->read()->count(3))->create();

        // visit dashboard without any query parameters
        $this->get(route('dashboard'))
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

        $this->get(route('dashboard').'?'.Arr::query(['cursor' => $cursor]))
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
        $this->get(route('dashboard').'?'.Arr::query(['feed_id' => $feedWithUnreadFeedItems->id]))
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
        $this->get(route('dashboard').'?'.Arr::query(['feed_id' => $feedWithoutUnreadFeedItems->id]))
            ->assertRedirect(route('dashboard'));
    }

    public function test_cannot_access_as_guest(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertRedirect('/login');
    }
}
