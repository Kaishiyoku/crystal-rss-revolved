<?php

namespace Policies;

use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserPolicyTest extends TestCase
{
    use RefreshDatabase;

    private UserPolicy $userPolicy;

    public function __construct(string $name)
    {
        parent::__construct($name);

        $this->userPolicy = new UserPolicy;
    }

    /**
     * An admin user should always be able to viewAny (e.g. the index page).
     */
    public function test_view_any_as_admin(): void
    {
        $this->actingAs($user = User::factory()->admin()->create());

        static::assertTrue($this->userPolicy->viewAny($user));
    }

    /**
     * A normal user should not be able to viewAny (e.g. the index page).
     */
    public function test_view_any_as_normal_user(): void
    {
        $this->actingAs($user = User::factory()->create());

        static::assertFalse($this->userPolicy->viewAny($user));
    }

    /**
     * An admin user should never be able to view a specific user because there's no user details page.
     */
    public function test_view(): void
    {
        $this->actingAs($ownUser = User::factory()->admin()->create());
        $user = User::factory()->create();

        static::assertFalse($this->userPolicy->view($ownUser, $user));
    }

    /**
     * An admin user should never be able to create a user because there's no user creation page.
     */
    public function test_create(): void
    {
        $this->actingAs($user = User::factory()->admin()->create());

        static::assertFalse($this->userPolicy->create($user));
    }

    /**
     * An admin user should never be able to update a user because there's no user update page.
     */
    public function test_update(): void
    {
        $this->actingAs($ownUser = User::factory()->admin()->create());
        $user = User::factory()->create();

        static::assertFalse($this->userPolicy->update($ownUser, $user));
    }

    /**
     * An admin user should be able to delete another user.
     */
    public function test_delete(): void
    {
        $this->actingAs($ownUser = User::factory()->admin()->create());
        $user = User::factory()->create();

        static::assertTrue($this->userPolicy->delete($ownUser, $user));
    }

    /**
     * An admin user should not be able to delete his own user.
     */
    public function test_cannot_delete_own_user(): void
    {
        $this->actingAs($user = User::factory()->create());

        static::assertFalse($this->userPolicy->delete($user, $user));
    }

    /**
     * A normal user should not be able to delete another user.
     */
    public function test_cannot_delete_as_normal_user(): void
    {
        $this->actingAs($ownUser = User::factory()->create());
        $user = User::factory()->create();

        static::assertFalse($this->userPolicy->delete($ownUser, $user));
    }

    /**
     * An admin user should never be able to restore a specific user because users aren't soft deletable.
     */
    public function test_restore(): void
    {
        $this->actingAs($ownUser = User::factory()->admin()->create());
        $user = User::factory()->create();

        static::assertFalse($this->userPolicy->restore($ownUser, $user));
    }

    /**
     * An admin user should never be able to force delete a specific user because users aren't soft deletable.
     */
    public function test_force_delete(): void
    {
        $this->actingAs($ownUser = User::factory()->admin()->create());
        $user = User::factory()->create();

        static::assertFalse($this->userPolicy->forceDelete($ownUser, $user));
    }
}
