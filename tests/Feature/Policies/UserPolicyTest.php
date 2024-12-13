<?php

use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class);
uses(RefreshDatabase::class);

/**
 * An admin user should always be able to viewAny (e.g. the index page).
 */
test('view any as admin', function () {
    $this->actingAs($user = User::factory()->admin()->create());

    static::assertTrue($this->userPolicy->viewAny($user));
});

/**
 * A normal user should not be able to viewAny (e.g. the index page).
 */
test('view any as normal user', function () {
    $this->actingAs($user = User::factory()->create());

    static::assertFalse($this->userPolicy->viewAny($user));
});

/**
 * An admin user should never be able to view a specific user because there's no user details page.
 */
test('view', function () {
    $this->actingAs($ownUser = User::factory()->admin()->create());
    $user = User::factory()->create();

    static::assertFalse($this->userPolicy->view($ownUser, $user));
});

/**
 * An admin user should never be able to create a user because there's no user creation page.
 */
test('create', function () {
    $this->actingAs($user = User::factory()->admin()->create());

    static::assertFalse($this->userPolicy->create($user));
});

/**
 * An admin user should never be able to update a user because there's no user update page.
 */
test('update', function () {
    $this->actingAs($ownUser = User::factory()->admin()->create());
    $user = User::factory()->create();

    static::assertFalse($this->userPolicy->update($ownUser, $user));
});

/**
 * An admin user should be able to delete another user.
 */
test('delete', function () {
    $this->actingAs($ownUser = User::factory()->admin()->create());
    $user = User::factory()->create();

    static::assertTrue($this->userPolicy->delete($ownUser, $user));
});

/**
 * An admin user should not be able to delete his own user.
 */
test('cannot delete own user', function () {
    $this->actingAs($user = User::factory()->create());

    static::assertFalse($this->userPolicy->delete($user, $user));
});

/**
 * A normal user should not be able to delete another user.
 */
test('cannot delete as normal user', function () {
    $this->actingAs($ownUser = User::factory()->create());
    $user = User::factory()->create();

    static::assertFalse($this->userPolicy->delete($ownUser, $user));
});

/**
 * An admin user should never be able to restore a specific user because users aren't soft deletable.
 */
test('restore', function () {
    $this->actingAs($ownUser = User::factory()->admin()->create());
    $user = User::factory()->create();

    static::assertFalse($this->userPolicy->restore($ownUser, $user));
});

/**
 * An admin user should never be able to force delete a specific user because users aren't soft deletable.
 */
test('force delete', function () {
    $this->actingAs($ownUser = User::factory()->admin()->create());
    $user = User::factory()->create();

    static::assertFalse($this->userPolicy->forceDelete($ownUser, $user));
});

// Helpers
function __construct(string $name)
{
    parent::__construct($name);

    test()->userPolicy = new UserPolicy;
}
