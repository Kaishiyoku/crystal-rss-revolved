<?php

use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

beforeEach(function () {
    test()->userPolicy = new UserPolicy;
});

/**
 * An admin user should always be able to viewAny (e.g. the index page).
 */
test('view any as admin', function () {
    actingAs($user = User::factory()->admin()->create());

    expect($this->userPolicy->viewAny($user))->toBeTrue();
});

/**
 * A normal user should not be able to viewAny (e.g. the index page).
 */
test('view any as normal user', function () {
    actingAs($user = User::factory()->create());

    expect($this->userPolicy->viewAny($user))->toBeFalse();
});

/**
 * An admin user should never be able to view a specific user because there's no user details page.
 */
test('view', function () {
    actingAs($ownUser = User::factory()->admin()->create());
    $user = User::factory()->create();

    expect($this->userPolicy->view($ownUser, $user))->toBeFalse();
});

/**
 * An admin user should never be able to create a user because there's no user creation page.
 */
test('create', function () {
    actingAs($user = User::factory()->admin()->create());

    expect($this->userPolicy->create($user))->toBeFalse();
});

/**
 * An admin user should never be able to update a user because there's no user update page.
 */
test('update', function () {
    actingAs($ownUser = User::factory()->admin()->create());
    $user = User::factory()->create();

    expect($this->userPolicy->update($ownUser, $user))->toBeFalse();
});

/**
 * An admin user should be able to delete another user.
 */
test('delete', function () {
    actingAs($ownUser = User::factory()->admin()->create());
    $user = User::factory()->create();

    expect($this->userPolicy->delete($ownUser, $user))->toBeTrue();
});

/**
 * An admin user should not be able to delete his own user.
 */
test('cannot delete own user', function () {
    actingAs($user = User::factory()->create());

    expect($this->userPolicy->delete($user, $user))->toBeFalse();
});

/**
 * A normal user should not be able to delete another user.
 */
test('cannot delete as normal user', function () {
    actingAs($ownUser = User::factory()->create());
    $user = User::factory()->create();

    expect($this->userPolicy->delete($ownUser, $user))->toBeFalse();
});

/**
 * An admin user should never be able to restore a specific user because users aren't soft deletable.
 */
test('restore', function () {
    actingAs($ownUser = User::factory()->admin()->create());
    $user = User::factory()->create();

    expect($this->userPolicy->restore($ownUser, $user))->toBeFalse();
});

/**
 * An admin user should never be able to force delete a specific user because users aren't soft deletable.
 */
test('force delete', function () {
    actingAs($ownUser = User::factory()->admin()->create());
    $user = User::factory()->create();

    expect($this->userPolicy->forceDelete($ownUser, $user))->toBeFalse();
});
