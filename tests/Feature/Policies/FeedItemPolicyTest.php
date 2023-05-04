<?php

namespace Policies;

use App\Models\Feed;
use App\Models\FeedItem;
use App\Models\User;
use App\Policies\FeedItemPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeedItemPolicyTest extends TestCase
{
    use RefreshDatabase;

    private FeedItemPolicy $feedItemPolicy;

    public function __construct(string $name)
    {
        parent::__construct($name);

        $this->feedItemPolicy = new FeedItemPolicy();
    }

    /**
     * A user should never be able to viewAny (e.g. the index page).
     */
    public function test_view_any(): void
    {
        $this->actingAs($user = User::factory()->create());

        static::assertFalse($this->feedItemPolicy->viewAny($user));
    }

    /**
     * A user should never be able to view a specific feed item because there's no feed item details page.
     */
    public function test_view(): void
    {
        $this->actingAs($user = User::factory()->create());
        $feed = Feed::factory()->for($user)->create();
        $feedItem = FeedItem::factory()->for($feed)->create();

        static::assertFalse($this->feedItemPolicy->view($user, $feedItem));
    }

    /**
     * A user should never be able to create a new feed item.
     */
    public function test_create(): void
    {
        $this->actingAs($user = User::factory()->create());

        static::assertFalse($this->feedItemPolicy->create($user));
    }

    /**
     * A user should be able to update his own feed item.
     */
    public function test_update_own_feed_item(): void
    {
        $this->actingAs($user = User::factory()->create());
        $feed = Feed::factory()->for($user)->create();
        $feedItem = FeedItem::factory()->for($feed)->create();

        static::assertTrue($this->feedItemPolicy->update($user, $feedItem));
    }

    /**
     * A user should not be able to update a feed item of another user.
     */
    public function test_cannot_update_feed_item_of_another_user(): void
    {
        $this->actingAs($user = User::factory()->create());
        $feedItemOfAnotherUser = FeedItem::factory()->create();

        static::assertFalse($this->feedItemPolicy->update($user, $feedItemOfAnotherUser));
    }

    /**
     * A user should never be able to delete a specific feed item.
     */
    public function test_delete(): void
    {
        $this->actingAs($user = User::factory()->create());
        $feed = Feed::factory()->for($user)->create();
        $feedItem = FeedItem::factory()->for($feed)->create();

        static::assertFalse($this->feedItemPolicy->delete($user, $feedItem));
    }

    /**
     * A user should never be able to restore a specific feed item because feed items aren't soft deletable.
     */
    public function test_restore(): void
    {
        $this->actingAs($user = User::factory()->create());
        $feed = Feed::factory()->for($user)->create();
        $feedItem = FeedItem::factory()->for($feed)->create();

        static::assertFalse($this->feedItemPolicy->restore($user, $feedItem));
    }

    /**
     * A user should never be able to force delete a specific feed item because feed items aren't soft deletable.
     */
    public function test_force_delete(): void
    {
        $this->actingAs($user = User::factory()->create());
        $feed = Feed::factory()->for($user)->create();
        $feedItem = FeedItem::factory()->for($feed)->create();

        static::assertFalse($this->feedItemPolicy->forceDelete($user, $feedItem));
    }
}
