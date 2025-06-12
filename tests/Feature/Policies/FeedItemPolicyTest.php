<?php

use App\Models\Feed;
use App\Models\FeedItem;
use App\Models\User;
use App\Policies\FeedItemPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

beforeEach(function () {
    test()->feedItemPolicy = new FeedItemPolicy;
});

/**
 * A user should never be able to viewAny (e.g. the index page).
 */
test('view any', function () {
    actingAs($user = User::factory()->create());

    expect($this->feedItemPolicy->viewAny($user))->toBeFalse();
});

/**
 * A user should never be able to view a specific feed item because there's no feed item details page.
 */
test('view', function () {
    actingAs($user = User::factory()->create());
    $feed = Feed::factory()->for($user)->create();
    $feedItem = FeedItem::factory()->for($feed)->create();

    expect($this->feedItemPolicy->view($user, $feedItem))->toBeFalse();
});

/**
 * A user should never be able to create a new feed item.
 */
test('create', function () {
    actingAs($user = User::factory()->create());

    expect($this->feedItemPolicy->create($user))->toBeFalse();
});

/**
 * A user should be able to update his own feed item.
 */
test('update own feed item', function () {
    actingAs($user = User::factory()->create());
    $feed = Feed::factory()->for($user)->create();
    $feedItem = FeedItem::factory()->for($feed)->create();

    expect($this->feedItemPolicy->update($user, $feedItem))->toBeTrue();
});

/**
 * A user should not be able to update a feed item of another user.
 */
test('cannot update feed item of another user', function () {
    actingAs($user = User::factory()->create());
    $feedItemOfAnotherUser = FeedItem::factory()->create();

    expect($this->feedItemPolicy->update($user, $feedItemOfAnotherUser))->toBeFalse();
});

/**
 * A user should never be able to delete a specific feed item.
 */
test('delete', function () {
    actingAs($user = User::factory()->create());
    $feed = Feed::factory()->for($user)->create();
    $feedItem = FeedItem::factory()->for($feed)->create();

    expect($this->feedItemPolicy->delete($user, $feedItem))->toBeFalse();
});

/**
 * A user should never be able to restore a specific feed item because feed items aren't soft deletable.
 */
test('restore', function () {
    actingAs($user = User::factory()->create());
    $feed = Feed::factory()->for($user)->create();
    $feedItem = FeedItem::factory()->for($feed)->create();

    expect($this->feedItemPolicy->restore($user, $feedItem))->toBeFalse();
});

/**
 * A user should never be able to force delete a specific feed item because feed items aren't soft deletable.
 */
test('force delete', function () {
    actingAs($user = User::factory()->create());
    $feed = Feed::factory()->for($user)->create();
    $feedItem = FeedItem::factory()->for($feed)->create();

    expect($this->feedItemPolicy->forceDelete($user, $feedItem))->toBeFalse();
});

/**
 * A user should be able to generate a PDF of their own feed item.
 */
test('pdf of own feed item', function () {
    actingAs($user = User::factory()->create());
    $feed = Feed::factory()->for($user)->create();
    $feedItem = FeedItem::factory()->for($feed)->create();

    expect($this->feedItemPolicy->pdf($user, $feedItem))->toBeTrue();
});

/**
 * A user should not be able to generate a PDF of a feed item owned by another user.
 */
test('pdf of feed item of another user', function () {
    actingAs($user = User::factory()->create());
    $anotherUser = User::factory()->create();
    $feed = Feed::factory()->for($anotherUser)->create();
    $feedItem = FeedItem::factory()->for($feed)->create();

    expect($this->feedItemPolicy->pdf($user, $feedItem))->toBeFalse();
});

test('pdf of feed item when pdf export is disabled for the feed', function () {
    actingAs($user = User::factory()->create());
    $feed = Feed::factory()->for($user)->state(['is_pdf_export_enabled' => false])->create();
    $feedItem = FeedItem::factory()->for($feed)->create();

    expect($this->feedItemPolicy->pdf($user, $feedItem))->toBeFalse();
});
