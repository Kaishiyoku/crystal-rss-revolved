<?php

use App\Models\Feed;
use App\Models\User;
use App\Policies\FeedPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

beforeEach(function () {
    test()->feedPolicy = new FeedPolicy;
});

/**
 * A user should always be able to viewAny (e.g. the index page).
 */
test('view any', function () {
    actingAs($user = User::factory()->create());

    expect($this->feedPolicy->viewAny($user))->toBeTrue();
});

/**
 * A user should never be able to view a specific feed because there's no feed details page.
 */
test('view', function () {
    actingAs($user = User::factory()->create());
    $feed = Feed::factory()->for($user)->create();

    expect($this->feedPolicy->view($user, $feed))->toBeFalse();
});

/**
 * A user should always be able to create a new feed.
 */
test('create', function () {
    actingAs($user = User::factory()->create());

    expect($this->feedPolicy->create($user))->toBeTrue();
});

/**
 * A user should be able to update his own feed.
 */
test('update own feed', function () {
    actingAs($user = User::factory()->create());
    $feed = Feed::factory()->for($user)->create();

    expect($this->feedPolicy->update($user, $feed))->toBeTrue();
});

/**
 * A user should not be able to update a feed of another user.
 */
test('cannot update feed of another user', function () {
    actingAs($user = User::factory()->create());
    $feedOfAnotherUser = Feed::factory()->create();

    expect($this->feedPolicy->update($user, $feedOfAnotherUser))->toBeFalse();
});

/**
 * A user should be able to delete his own feed.
 */
test('delete own feed', function () {
    actingAs($user = User::factory()->create());
    $feed = Feed::factory()->for($user)->create();

    expect($this->feedPolicy->delete($user, $feed))->toBeTrue();
});

/**
 * A user should not be able to delete a feed of another user.
 */
test('cannot delete feed of another user', function () {
    actingAs($user = User::factory()->create());
    $feedOfAnotherUser = Feed::factory()->create();

    expect($this->feedPolicy->delete($user, $feedOfAnotherUser))->toBeFalse();
});

/**
 * A user should never be able to restore a specific feed because feeds aren't soft deletable.
 */
test('restore', function () {
    actingAs($user = User::factory()->create());
    $feed = Feed::factory()->for($user)->create();

    expect($this->feedPolicy->restore($user, $feed))->toBeFalse();
});

/**
 * A user should never be able to force delete a specific feed because feeds aren't soft deletable.
 */
test('force delete', function () {
    actingAs($user = User::factory()->create());
    $feed = Feed::factory()->for($user)->create();

    expect($this->feedPolicy->forceDelete($user, $feed))->toBeFalse();
});
