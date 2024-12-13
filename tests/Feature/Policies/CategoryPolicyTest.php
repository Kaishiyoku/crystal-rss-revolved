<?php

use App\Models\Category;
use App\Models\Feed;
use App\Models\User;
use App\Policies\CategoryPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

beforeEach(function () {
    test()->categoryPolicy = new CategoryPolicy;
});

/**
 * A user should always be able to viewAny (e.g. the index page).
 */
test('view any', function () {
    actingAs($user = User::factory()->create());

    expect($this->categoryPolicy->viewAny($user))->toBeTrue();
});

/**
 * A user should never be able to view a specific category because there's no category details page.
 */
test('view', function () {
    actingAs($user = User::factory()->create());
    $category = Category::factory()->for($user)->create();

    expect($this->categoryPolicy->view($user, $category))->toBeFalse();
});

/**
 * A user should always be able to create a new category.
 */
test('create', function () {
    actingAs($user = User::factory()->create());

    expect($this->categoryPolicy->create($user))->toBeTrue();
});

/**
 * A user should be able to update his own category.
 */
test('update own category', function () {
    actingAs($user = User::factory()->create());
    $category = Category::factory()->for($user)->create();

    expect($this->categoryPolicy->update($user, $category))->toBeTrue();
});

/**
 * A user should not be able to update a category of another user.
 */
test('cannot update category of another user', function () {
    actingAs($user = User::factory()->create());
    $categoryOfAnotherUser = Category::factory()->create();

    expect($this->categoryPolicy->update($user, $categoryOfAnotherUser))->toBeFalse();
});

/**
 * A user should be able to delete his own category.
 */
test('delete own category', function () {
    actingAs($user = User::factory()->create());
    $category = Category::factory()->for($user)->create();

    expect($this->categoryPolicy->delete($user, $category))->toBeTrue();
});

/**
 * A user should not be able to delete a category of another user.
 */
test('cannot delete category of another user', function () {
    actingAs($user = User::factory()->create());
    $categoryOfAnotherUser = Category::factory()->create();

    expect($this->categoryPolicy->delete($user, $categoryOfAnotherUser))->toBeFalse();
});

/**
 * A user should not be able to delete a category which still has feeds.
 */
test('cannot delete category with feeds', function () {
    actingAs($user = User::factory()->create());
    $category = Category::factory()->for($user)->has(Feed::factory())->create();

    expect($this->categoryPolicy->delete($user, $category))->toBeFalse();
});

/**
 * A user should never be able to restore a specific category because categories aren't soft deletable.
 */
test('restore', function () {
    actingAs($user = User::factory()->create());
    $category = Category::factory()->for($user)->create();

    expect($this->categoryPolicy->restore($user, $category))->toBeFalse();
});

/**
 * A user should never be able to force delete a specific category because categories aren't soft deletable.
 */
test('force delete', function () {
    actingAs($user = User::factory()->create());
    $category = Category::factory()->for($user)->create();

    expect($this->categoryPolicy->forceDelete($user, $category))->toBeFalse();
});
