<?php

namespace Tests\Feature\Policies;

use App\Models\Category;
use App\Models\Feed;
use App\Models\User;
use App\Policies\CategoryPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryPolicyTest extends TestCase
{
    use RefreshDatabase;

    private CategoryPolicy $categoryPolicy;

    public function __construct(string $name)
    {
        parent::__construct($name);

        $this->categoryPolicy = new CategoryPolicy();
    }

    /**
     * A user should always be able to viewAny (e.g. the index page).
     */
    public function test_view_any(): void
    {
        $this->actingAs($user = User::factory()->create());

        static::assertTrue($this->categoryPolicy->viewAny($user));
    }

    /**
     * A user should never be able to view a specific category because there's no category details page.
     */
    public function test_view(): void
    {
        $this->actingAs($user = User::factory()->create());
        $category = Category::factory()->for($user)->create();

        static::assertFalse($this->categoryPolicy->view($user, $category));
    }

    /**
     * A user should always be able to create a new category.
     */
    public function test_create(): void
    {
        $this->actingAs($user = User::factory()->create());

        static::assertTrue($this->categoryPolicy->create($user));
    }

    /**
     * A user should be able to update his own category.
     */
    public function test_update_own_category(): void
    {
        $this->actingAs($user = User::factory()->create());
        $category = Category::factory()->for($user)->create();

        static::assertTrue($this->categoryPolicy->update($user, $category));
    }

    /**
     * A user should not be able to update a category of another user.
     */
    public function test_cannot_update_category_of_another_user(): void
    {
        $this->actingAs($user = User::factory()->create());
        $categoryOfAnotherUser = Category::factory()->create();

        static::assertFalse($this->categoryPolicy->update($user, $categoryOfAnotherUser));
    }

    /**
     * A user should be able to delete his own category.
     */
    public function test_delete_own_category(): void
    {
        $this->actingAs($user = User::factory()->create());
        $category = Category::factory()->for($user)->create();

        static::assertTrue($this->categoryPolicy->delete($user, $category));
    }

    /**
     * A user should not be able to delete a category of another user.
     */
    public function test_cannot_delete_category_of_another_user(): void
    {
        $this->actingAs($user = User::factory()->create());
        $categoryOfAnotherUser = Category::factory()->create();

        static::assertFalse($this->categoryPolicy->delete($user, $categoryOfAnotherUser));
    }

    /**
     * A user should not be able to delete a category which still has feeds.
     */
    public function test_cannot_delete_category_with_feeds(): void
    {
        $this->actingAs($user = User::factory()->create());
        $category = Category::factory()->for($user)->has(Feed::factory())->create();

        static::assertFalse($this->categoryPolicy->delete($user, $category));
    }

    /**
     * A user should never be able to restore a specific category because categories aren't soft deletable.
     */
    public function test_restore(): void
    {
        $this->actingAs($user = User::factory()->create());
        $category = Category::factory()->for($user)->create();

        static::assertFalse($this->categoryPolicy->restore($user, $category));
    }

    /**
     * A user should never be able to force delete a specific category because categories aren't soft deletable.
     */
    public function test_force_delete(): void
    {
        $this->actingAs($user = User::factory()->create());
        $category = Category::factory()->for($user)->create();

        static::assertFalse($this->categoryPolicy->forceDelete($user, $category));
    }
}
