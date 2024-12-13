<?php

use App\Models\Category;
use App\Models\Feed;
use App\Models\User;
use App\Policies\CategoryPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * A user should always be able to viewAny (e.g. the index page).
 */
test('view any', function () {
    $this->actingAs($user = User::factory()->create());

    static::assertTrue($this->categoryPolicy->viewAny($user));
});

/**
 * A user should never be able to view a specific category because there's no category details page.
 */
test('view', function () {
    $this->actingAs($user = User::factory()->create());
    $category = Category::factory()->for($user)->create();

    static::assertFalse($this->categoryPolicy->view($user, $category));
});

/**
 * A user should always be able to create a new category.
 */
test('create', function () {
    $this->actingAs($user = User::factory()->create());

    static::assertTrue($this->categoryPolicy->create($user));
});

/**
 * A user should be able to update his own category.
 */
test('update own category', function () {
    $this->actingAs($user = User::factory()->create());
    $category = Category::factory()->for($user)->create();

    static::assertTrue($this->categoryPolicy->update($user, $category));
});

/**
 * A user should not be able to update a category of another user.
 */
test('cannot update category of another user', function () {
    $this->actingAs($user = User::factory()->create());
    $categoryOfAnotherUser = Category::factory()->create();

    static::assertFalse($this->categoryPolicy->update($user, $categoryOfAnotherUser));
});

/**
 * A user should be able to delete his own category.
 */
test('delete own category', function () {
    $this->actingAs($user = User::factory()->create());
    $category = Category::factory()->for($user)->create();

    static::assertTrue($this->categoryPolicy->delete($user, $category));
});

/**
 * A user should not be able to delete a category of another user.
 */
test('cannot delete category of another user', function () {
    $this->actingAs($user = User::factory()->create());
    $categoryOfAnotherUser = Category::factory()->create();

    static::assertFalse($this->categoryPolicy->delete($user, $categoryOfAnotherUser));
});

/**
 * A user should not be able to delete a category which still has feeds.
 */
test('cannot delete category with feeds', function () {
    $this->actingAs($user = User::factory()->create());
    $category = Category::factory()->for($user)->has(Feed::factory())->create();

    static::assertFalse($this->categoryPolicy->delete($user, $category));
});

/**
 * A user should never be able to restore a specific category because categories aren't soft deletable.
 */
test('restore', function () {
    $this->actingAs($user = User::factory()->create());
    $category = Category::factory()->for($user)->create();

    static::assertFalse($this->categoryPolicy->restore($user, $category));
});

/**
 * A user should never be able to force delete a specific category because categories aren't soft deletable.
 */
test('force delete', function () {
    $this->actingAs($user = User::factory()->create());
    $category = Category::factory()->for($user)->create();

    static::assertFalse($this->categoryPolicy->forceDelete($user, $category));
});

// Helpers
function __construct(string $name)
{
    parent::__construct($name);

    test()->categoryPolicy = new CategoryPolicy;
}
