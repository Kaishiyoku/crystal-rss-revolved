<?php

use App\Models\Feed;
use App\Models\FeedItem;
use App\Models\User;
use App\Policies\FeedItemPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

/**
 * A user should never be able to viewAny (e.g. the index page).
 */
test('view any', function () {
    $this->actingAs($user = User::factory()->create());

    static::assertFalse($this->feedItemPolicy->viewAny($user));
});

/**
 * A user should never be able to view a specific feed item because there's no feed item details page.
 */
test('view', function () {
    $this->actingAs($user = User::factory()->create());
    $feed = Feed::factory()->for($user)->create();
    $feedItem = FeedItem::factory()->for($feed)->create();

    static::assertFalse($this->feedItemPolicy->view($user, $feedItem));
});

/**
 * A user should never be able to create a new feed item.
 */
test('create', function () {
    $this->actingAs($user = User::factory()->create());

    static::assertFalse($this->feedItemPolicy->create($user));
});

/**
 * A user should be able to update his own feed item.
 */
test('update own feed item', function () {
    $this->actingAs($user = User::factory()->create());
    $feed = Feed::factory()->for($user)->create();
    $feedItem = FeedItem::factory()->for($feed)->create();

    static::assertTrue($this->feedItemPolicy->update($user, $feedItem));
});

/**
 * A user should not be able to update a feed item of another user.
 */
test('cannot update feed item of another user', function () {
    $this->actingAs($user = User::factory()->create());
    $feedItemOfAnotherUser = FeedItem::factory()->create();

    static::assertFalse($this->feedItemPolicy->update($user, $feedItemOfAnotherUser));
});

/**
 * A user should never be able to delete a specific feed item.
 */
test('delete', function () {
    $this->actingAs($user = User::factory()->create());
    $feed = Feed::factory()->for($user)->create();
    $feedItem = FeedItem::factory()->for($feed)->create();

    static::assertFalse($this->feedItemPolicy->delete($user, $feedItem));
});

/**
 * A user should never be able to restore a specific feed item because feed items aren't soft deletable.
 */
test('restore', function () {
    $this->actingAs($user = User::factory()->create());
    $feed = Feed::factory()->for($user)->create();
    $feedItem = FeedItem::factory()->for($feed)->create();

    static::assertFalse($this->feedItemPolicy->restore($user, $feedItem));
});

/**
 * A user should never be able to force delete a specific feed item because feed items aren't soft deletable.
 */
test('force delete', function () {
    $this->actingAs($user = User::factory()->create());
    $feed = Feed::factory()->for($user)->create();
    $feedItem = FeedItem::factory()->for($feed)->create();

    static::assertFalse($this->feedItemPolicy->forceDelete($user, $feedItem));
});

// Helpers
function __construct(string $name)
{
    parent::__construct($name);

    test()->feedItemPolicy = new FeedItemPolicy;
}
