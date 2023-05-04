<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Feed;
use App\Models\FeedItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ToggleFeedItemControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_marks_feed_item_as_read(): void
    {
        $this->freezeTime();

        $this->actingAs($user = User::factory()->create());

        $feed = Feed::factory()->recycle($user)->create();
        $feedItem = FeedItem::factory()->recycle($feed)->state(['read_at' => null])->create();

        $this->put(route('toggle-feed-item', $feedItem))
            ->assertOk()
            ->assertJsonPath('read_at', now()->micro(0)->toJSON());
    }

    public function test_marks_feed_item_as_unread(): void
    {
        $this->freezeTime();

        $this->actingAs($user = User::factory()->create());

        $feed = Feed::factory()->recycle($user)->create();
        $feedItem = FeedItem::factory()->recycle($feed)->state(['read_at' => now()])->create();

        $this->put(route('toggle-feed-item', $feedItem))
            ->assertOk()
            ->assertJsonPath(null);
    }

    public function test_cannot_toggle_feed_item_of_another_user(): void
    {
        $this->actingAs(User::factory()->create());

        $feed = Feed::factory()->create();
        $feedItem = FeedItem::factory()->recycle($feed)->create();

        $this->put(route('toggle-feed-item', $feedItem))
            ->assertForbidden();
    }
}
