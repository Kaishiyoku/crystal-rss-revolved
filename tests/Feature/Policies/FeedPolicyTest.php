<?php

use App\Models\Feed;
use App\Models\User;
use App\Policies\FeedPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * A user should always be able to viewAny (e.g. the index page).
 */
test('view any', function () {
    $this->actingAs($user = User::factory()->create());

    static::assertTrue($this->feedPolicy->viewAny($user));
});

/**
 * A user should never be able to view a specific feed because there's no feed details page.
 */
test('view', function () {
    $this->actingAs($user = User::factory()->create());
    $feed = Feed::factory()->for($user)->create();

    static::assertFalse($this->feedPolicy->view($user, $feed));
});

/**
 * A user should always be able to create a new feed.
 */
test('create', function () {
    $this->actingAs($user = User::factory()->create());

    static::assertTrue($this->feedPolicy->create($user));
});

/**
 * A user should be able to update his own feed.
 */
test('update own feed', function () {
    $this->actingAs($user = User::factory()->create());
    $feed = Feed::factory()->for($user)->create();

    static::assertTrue($this->feedPolicy->update($user, $feed));
});

/**
 * A user should not be able to update a feed of another user.
 */
test('cannot update feed of another user', function () {
    $this->actingAs($user = User::factory()->create());
    $feedOfAnotherUser = Feed::factory()->create();

    static::assertFalse($this->feedPolicy->update($user, $feedOfAnotherUser));
});

/**
 * A user should be able to delete his own feed.
 */
test('delete own feed', function () {
    $this->actingAs($user = User::factory()->create());
    $feed = Feed::factory()->for($user)->create();

    static::assertTrue($this->feedPolicy->delete($user, $feed));
});

/**
 * A user should not be able to delete a feed of another user.
 */
test('cannot delete feed of another user', function () {
    $this->actingAs($user = User::factory()->create());
    $feedOfAnotherUser = Feed::factory()->create();

    static::assertFalse($this->feedPolicy->delete($user, $feedOfAnotherUser));
});

/**
 * A user should never be able to restore a specific feed because feeds aren't soft deletable.
 */
test('restore', function () {
    $this->actingAs($user = User::factory()->create());
    $feed = Feed::factory()->for($user)->create();

    static::assertFalse($this->feedPolicy->restore($user, $feed));
});

/**
 * A user should never be able to force delete a specific feed because feeds aren't soft deletable.
 */
test('force delete', function () {
    $this->actingAs($user = User::factory()->create());
    $feed = Feed::factory()->for($user)->create();

    static::assertFalse($this->feedPolicy->forceDelete($user, $feed));
});

// Helpers
function __construct(string $name)
{
    parent::__construct($name);

    test()->feedPolicy = new FeedPolicy;
}
