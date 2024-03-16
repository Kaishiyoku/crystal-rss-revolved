<?php

namespace Http\Controllers\Api;

use App\Models\Feed;
use App\Models\FeedItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index(): void
    {
        $this->actingAs($user = User::factory()->create());

        $feedWithUnreadFeedItems = Feed::factory()->for($user)->has(FeedItem::factory()->unread()->count(15))->create();
        $anotherFeedWithUnreadFeedItems = Feed::factory()->for($user)->has(FeedItem::factory()->unread()->count(5))->create();
        $feedWithoutUnreadFeedItems = Feed::factory()->for($user)->has(FeedItem::factory()->read()->count(6))->create();

        // visit dashboard without any query parameters
        $this->json('get', route('api.dashboard'))
            ->assertJsonStructure([
                'selectedFeed',
                'totalNumberOfFeedItems',
                'unreadFeeds',
                'feedItems' => [
                    'data',
                    'path',
                    'per_page',
                    'next_cursor',
                    'next_page_url',
                    'prev_cursor',
                    'prev_page_url',
                ],
                'currentCursor',
            ])
            ->assertJsonPath('selectedFeed', null)
            ->assertJsonPath('totalNumberOfFeedItems', 20)
            ->assertJsonCount(2, 'unreadFeeds')
            ->assertJsonCount(15, 'feedItems.data')
            ->assertJsonPath('currentCursor', null);

        // visit dashboard with cursor query parameter
        $feedItems = Auth::user()->feedItems()
            ->unread()
            ->cursorPaginate()
            ->withQueryString();

        $cursor = Arr::get($feedItems->toArray(), 'next_cursor');

        $this->json('get', route('api.dashboard').'?'.Arr::query(['cursor' => $cursor]))
            ->assertJsonStructure([
                'selectedFeed',
                'totalNumberOfFeedItems',
                'unreadFeeds',
                'feedItems' => [
                    'data',
                    'path',
                    'per_page',
                    'next_cursor',
                    'next_page_url',
                    'prev_cursor',
                    'prev_page_url',
                ],
                'currentCursor',
            ])
            ->assertJsonPath('selectedFeed', null)
            ->assertJsonPath('totalNumberOfFeedItems', 20)
            ->assertJsonCount(2, 'unreadFeeds')
            ->assertJsonCount(5, 'feedItems.data')
            ->assertJsonPath('currentCursor', $cursor);

        // visit dashboard with selected feed
        $this->json('get', route('api.dashboard').'?'.Arr::query(['feed_id' => $feedWithUnreadFeedItems->id]))
            ->assertJsonStructure([
                'selectedFeed' => [
                    'id',
                    'name',
                    'feed_items_count',
                ],
                'totalNumberOfFeedItems',
                'unreadFeeds',
                'feedItems' => [
                    'data',
                    'path',
                    'per_page',
                    'next_cursor',
                    'next_page_url',
                    'prev_cursor',
                    'prev_page_url',
                ],
                'currentCursor',
            ])
            ->assertJsonPath('selectedFeed', [...$feedWithUnreadFeedItems->only(['id', 'name']), 'feed_items_count' => 15])
            ->assertJsonPath('totalNumberOfFeedItems', 20)
            ->assertJsonCount(2, 'unreadFeeds')
            ->assertJsonCount(15, 'feedItems.data')
            ->assertJsonPath('currentCursor', null);

        // visit dashboard with selected feed which has no unread feed items
        $this->get(route('api.dashboard').'?'.Arr::query(['feed_id' => $feedWithoutUnreadFeedItems->id]))
            ->assertJsonStructure([
                'selectedFeed',
                'totalNumberOfFeedItems',
                'unreadFeeds',
                'feedItems',
                'currentCursor',
            ])
            ->assertJsonPath('selectedFeed', null)
            ->assertJsonPath('totalNumberOfFeedItems', 20)
            ->assertJsonCount(2, 'unreadFeeds')
            ->assertJsonCount(0, 'feedItems.data')
            ->assertJsonPath('currentCursor', null);
    }

    public function test_cannot_access_as_guest(): void
    {
        $this->json('get', route('api.dashboard'))
            ->assertUnauthorized();
    }
}
