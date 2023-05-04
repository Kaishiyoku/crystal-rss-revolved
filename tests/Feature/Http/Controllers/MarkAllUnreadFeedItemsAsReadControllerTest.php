<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Feed;
use App\Models\FeedItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarkAllUnreadFeedItemsAsReadControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_marks_all_unread_feed_items_as_read_for_specific_user(): void
    {
        $this->freezeTime();

        $this->actingAs($user = User::factory()->create());

        Feed::factory(5)
            ->recycle($user)
            ->has(FeedItem::factory(10)->state(['read_at' => null]))
            ->has(FeedItem::factory(20)->state(['read_at' => now()]))
            ->create();

        static::assertSame(50, $user->feedItems()->unread()->count());
        static::assertSame(100, $user->feedItems()->whereNotNull('read_at')->count());

        $this->put(route('mark-all-as-read'))
            ->assertOk();

        static::assertSame(0, $user->feedItems()->unread()->count());
        static::assertSame(150, $user->feedItems()->whereNotNull('read_at')->count());
    }

    public function test_cannot_access_as_guest(): void
    {
        $this->put(route('mark-all-as-read'))
            ->assertRedirect('/login');
    }
}
