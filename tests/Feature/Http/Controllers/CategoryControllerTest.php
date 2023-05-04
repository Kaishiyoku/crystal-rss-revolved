<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\CategoryController;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_middleware_is_registered(): void
    {
        $middleware = collect((new CategoryController())->getMiddleware())
            ->map(fn(array $arr) => Arr::get($arr, 'middleware'))
            ->toArray();

        $expectedMiddleware = [
            'can:viewAny,App\Models\Category',
            'can:view,category',
            'can:create,App\Models\Category',
            'can:update,category',
            'can:delete,category',
        ];

        static::assertCount(5, $middleware);
        static::assertSame($expectedMiddleware, $middleware);
    }

    public function test_index(): void
    {
        $this->actingAs($user = User::factory()->create());

        Category::factory()->for($user)->create();

        $this->get(route('categories.index'))
            ->assertInertia(fn (Assert $page) => $page
                ->component('Categories/Index')
                ->count('categories', 1)
                ->where('canCreate', true)
            );
    }

    public function test_cannot_access_index_as_guest(): void
    {
        $response = $this->get(route('categories.index'));

        $response->assertRedirect('/login');
    }

    public function test_create(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get(route('categories.create'))
            ->assertInertia(fn (Assert $page) => $page
                ->has('category')
            );
    }

    public function test_store(): void
    {
        $this->actingAs($user = User::factory()->create());

        $expectedName = 'Test';

        // here we test if the unique rule is only applied to a specific user's categories
        Category::factory()->state(['name' => $expectedName]);

        $response = $this->post(route('categories.store'), ['name' => $expectedName]);

        $response->assertRedirect(route('categories.index'));
        static::assertSame(1, $user->categories()->count());
        static::assertSame($user->id, $user->categories()->first()->user_id);
        static::assertSame($expectedName, $user->categories()->first()->name);
    }

    public function test_store_validation_fails_due_to_duplicate_name(): void
    {
        $this->actingAs($user = User::factory()->has(Category::factory()->state(['name' => 'Test']))->create());

        $expectedName = 'Test';

        $this->get(route('categories.create'));
        $response = $this->post(route('categories.store'), ['name' => $expectedName]);

        $response->assertRedirect(route('categories.create'));
        $response->assertSessionHasErrors(['name' => 'The name has already been taken.']);
        static::assertSame(1, $user->categories()->count());
    }

    public function test_store_validation_fails_due_to_missing_data(): void
    {
        $this->actingAs($user = User::factory()->create());

        $this->get(route('categories.create'));
        $response = $this->post(route('categories.store'), ['name' => ' ']);

        $response->assertRedirect(route('categories.create'));
        $response->assertSessionHasErrors(['name' => 'The name field is required.']);
        static::assertSame(0, $user->categories()->count());
    }

    public function test_edit(): void
    {
        $this->actingAs($user = User::factory()->create());

        $category = Category::factory()->for($user)->create();

        $this->get(route('categories.edit', $category))
            ->assertInertia(fn (Assert $page) => $page
                ->has('category')
                ->where('canDelete', true)
            );
    }

    public function test_cannot_edit_category_of_another_user(): void
    {
        $this->actingAs(User::factory()->create());

        $category = Category::factory()->create();

        $this->get(route('categories.edit', $category))
            ->assertForbidden();
    }

    public function test_update(): void
    {
        $this->actingAs($user = User::factory()->create());

        $category = Category::factory()->for($user)->create();

        $expectedName = 'Test (updated)';

        // here we test if the unique rule is only applied to a specific user's categories
        Category::factory()->state(['name' => $expectedName]);

        $response = $this->put(route('categories.update', $category), ['name' => $expectedName]);

        $response->assertRedirect(route('categories.index'));
        static::assertSame($user->id, $user->categories()->first()->user_id);
        static::assertSame($expectedName, $user->categories()->first()->name);
    }

    public function test_cannot_update_category_of_another_user(): void
    {
        $this->actingAs(User::factory()->create());

        $category = Category::factory()->create();

        $this->put(route('categories.update', $category), ['name' => 'Test (updated)'])
            ->assertForbidden();
    }

    public function test_cannot_update_category_due_to_duplicate_name(): void
    {
        $this->actingAs($user = User::factory()->create());

        Category::factory()->state(['name' => 'Test 1'])->for($user)->create();
        $category = Category::factory()->state(['name' => 'Test 2'])->for($user)->create();

        $this->get(route('categories.edit', $category));

        $response = $this->put(route('categories.update', $category), ['name' => 'Test 1']);

        $response->assertRedirect(route('categories.edit', $category));
        $response->assertSessionHasErrors(['name' => 'The name has already been taken.']);
    }

    public function test_delete(): void
    {
        $this->actingAs($user = User::factory()->create());

        $category = Category::factory()->for($user)->create();

        $response = $this->delete(route('categories.destroy', $category));

        $response->assertRedirect(route('categories.index'));
        static::assertSame(0, $user->categories()->count());
    }

    public function test_cannot_delete_category_of_another_user(): void
    {
        $this->actingAs(User::factory()->create());

        $category = Category::factory()->create();

        $this->delete(route('categories.destroy', $category))
            ->assertForbidden();

        static::assertSame(1, Category::count());
    }
}
