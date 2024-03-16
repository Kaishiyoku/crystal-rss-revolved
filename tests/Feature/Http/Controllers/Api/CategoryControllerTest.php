<?php

namespace Http\Controllers\Api;

use App\Http\Controllers\Api\CategoryController;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_middleware_is_registered(): void
    {
        $middleware = collect((new CategoryController())->getMiddleware())
            ->map(fn (array $arr) => Arr::get($arr, 'middleware'))
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

        $category = Category::factory()->for($user)->create();

        $this->json('get', route('api.categories.index'))
            ->assertJson([
                'categories' => [$category->toArray()],
                'canCreate' => true,
            ]);
    }

    public function test_cannot_access_index_as_guest(): void
    {
        $this->json('get', route('api.categories.index'))
            ->assertUnauthorized();
    }

    public function test_store(): void
    {
        $this->actingAs($user = User::factory()->create());

        $expectedName = 'Test';

        // here we test if the unique rule is only applied to a specific user's categories
        Category::factory()->state(['name' => $expectedName]);

        $this->json('post', route('api.categories.store'), ['name' => $expectedName])
            ->assertOk()
            ->assertJson([]);
        static::assertSame(1, $user->categories()->count());
        static::assertSame($user->id, $user->categories()->first()->user_id);
        static::assertSame($expectedName, $user->categories()->first()->name);
    }

    public function test_store_validation_fails_due_to_duplicate_name(): void
    {
        $this->actingAs($user = User::factory()->has(Category::factory()->state(['name' => 'Test']))->create());

        $expectedName = 'Test';

        $this->json('post', route('api.categories.store'), ['name' => $expectedName])
            ->assertUnprocessable()
            ->assertJson([
                'message' => 'The Name has already been taken.',
                'errors' => [
                    'name' => ['The Name has already been taken.'],
                ],
            ]);
        static::assertSame(1, $user->categories()->count());
    }

    public function test_store_validation_fails_due_to_missing_data(): void
    {
        $this->actingAs($user = User::factory()->create());

        $this->json('post', route('api.categories.store'), ['name' => ' '])
            ->assertUnprocessable()
            ->assertJson([
                'message' => 'The Name field is required.',
                'errors' => [
                    'name' => ['The Name field is required.'],
                ],
            ]);
        static::assertSame(0, $user->categories()->count());
    }

    public function test_edit(): void
    {
        $this->actingAs($user = User::factory()->create());

        $category = Category::factory()->for($user)->create();

        $this->json('get', route('api.categories.edit', $category))
            ->assertJson([
                'category' => $category->toArray(),
                'canDelete' => true,
            ]);
    }

    public function test_cannot_edit_category_of_another_user(): void
    {
        $this->actingAs(User::factory()->create());

        $category = Category::factory()->create();

        $this->json('get', route('api.categories.edit', $category))
            ->assertForbidden();
    }

    public function test_update(): void
    {
        $this->actingAs($user = User::factory()->create());

        $category = Category::factory()->for($user)->create();

        $expectedName = 'Test (updated)';

        // here we test if the unique rule is only applied to a specific user's categories
        Category::factory()->state(['name' => $expectedName]);

        $this->json('put', route('api.categories.update', $category), ['name' => $expectedName])
            ->assertOk()
            ->assertJson([]);
        static::assertSame($user->id, $user->categories()->first()->user_id);
        static::assertSame($expectedName, $user->categories()->first()->name);
    }

    public function test_cannot_update_category_of_another_user(): void
    {
        $this->actingAs(User::factory()->create());

        $category = Category::factory()->create();

        $this->json('put', route('api.categories.update', $category), ['name' => 'Test (updated)'])
            ->assertForbidden();
    }

    public function test_cannot_update_category_due_to_duplicate_name(): void
    {
        $this->actingAs($user = User::factory()->create());

        Category::factory()->state(['name' => 'Test 1'])->for($user)->create();
        $category = Category::factory()->state(['name' => 'Test 2'])->for($user)->create();

        $this->json('put', route('api.categories.update', $category), ['name' => 'Test 1'])
            ->assertUnprocessable()
            ->assertJson([
                'message' => 'The Name has already been taken.',
                'errors' => [
                    'name' => ['The Name has already been taken.'],
                ],
            ]);
    }

    public function test_delete(): void
    {
        $this->actingAs($user = User::factory()->create());

        $category = Category::factory()->for($user)->create();

        $this->json('delete', route('api.categories.destroy', $category))
            ->assertOk()
            ->assertJson([]);
        static::assertSame(0, $user->categories()->count());
    }

    public function test_cannot_delete_category_of_another_user(): void
    {
        $this->actingAs(User::factory()->create());

        $category = Category::factory()->create();

        $this->json('delete', route('api.categories.destroy', $category))
            ->assertForbidden();

        static::assertSame(1, Category::count());
    }
}
