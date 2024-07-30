<?php

namespace Tests\Feature\Policies;

use App\Models\Feed;
use App\Models\User;
use App\Policies\FeedPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeedPolicyTest extends TestCase
{
    use RefreshDatabase;

    private FeedPolicy $feedPolicy;

    public function __construct(string $name)
    {
        parent::__construct($name);

        $this->feedPolicy = new FeedPolicy;
    }

    /**
     * A user should always be able to viewAny (e.g. the index page).
     */
    public function test_view_any(): void
    {
        $this->actingAs($user = User::factory()->create());

        static::assertTrue($this->feedPolicy->viewAny($user));
    }

    /**
     * A user should never be able to view a specific feed because there's no feed details page.
     */
    public function test_view(): void
    {
        $this->actingAs($user = User::factory()->create());
        $feed = Feed::factory()->for($user)->create();

        static::assertFalse($this->feedPolicy->view($user, $feed));
    }

    /**
     * A user should always be able to create a new feed.
     */
    public function test_create(): void
    {
        $this->actingAs($user = User::factory()->create());

        static::assertTrue($this->feedPolicy->create($user));
    }

    /**
     * A user should be able to update his own feed.
     */
    public function test_update_own_feed(): void
    {
        $this->actingAs($user = User::factory()->create());
        $feed = Feed::factory()->for($user)->create();

        static::assertTrue($this->feedPolicy->update($user, $feed));
    }

    /**
     * A user should not be able to update a feed of another user.
     */
    public function test_cannot_update_feed_of_another_user(): void
    {
        $this->actingAs($user = User::factory()->create());
        $feedOfAnotherUser = Feed::factory()->create();

        static::assertFalse($this->feedPolicy->update($user, $feedOfAnotherUser));
    }

    /**
     * A user should be able to delete his own feed.
     */
    public function test_delete_own_feed(): void
    {
        $this->actingAs($user = User::factory()->create());
        $feed = Feed::factory()->for($user)->create();

        static::assertTrue($this->feedPolicy->delete($user, $feed));
    }

    /**
     * A user should not be able to delete a feed of another user.
     */
    public function test_cannot_delete_feed_of_another_user(): void
    {
        $this->actingAs($user = User::factory()->create());
        $feedOfAnotherUser = Feed::factory()->create();

        static::assertFalse($this->feedPolicy->delete($user, $feedOfAnotherUser));
    }

    /**
     * A user should never be able to restore a specific feed because feeds aren't soft deletable.
     */
    public function test_restore(): void
    {
        $this->actingAs($user = User::factory()->create());
        $feed = Feed::factory()->for($user)->create();

        static::assertFalse($this->feedPolicy->restore($user, $feed));
    }

    /**
     * A user should never be able to force delete a specific feed because feeds aren't soft deletable.
     */
    public function test_force_delete(): void
    {
        $this->actingAs($user = User::factory()->create());
        $feed = Feed::factory()->for($user)->create();

        static::assertFalse($this->feedPolicy->forceDelete($user, $feed));
    }
}
