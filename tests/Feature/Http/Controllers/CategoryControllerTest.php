<?php

use App\Http\Controllers\CategoryController;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

uses(TestCase::class);
uses(RefreshDatabase::class);

test('middleware is registered', function () {
    $middleware = collect((new CategoryController)->getMiddleware())
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
});

test('index', function () {
    $this->actingAs($user = User::factory()->create());

    Category::factory()->for($user)->create();

    $this->get(route('categories.index'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Categories/Index')
            ->count('categories', 1)
            ->where('canCreate', true)
        );
});

test('cannot access index as guest', function () {
    $response = $this->get(route('categories.index'));

    $response->assertRedirect('/login');
});

test('create', function () {
    $this->actingAs(User::factory()->create());

    $this->get(route('categories.create'))
        ->assertInertia(fn (Assert $page) => $page
            ->has('category')
        );
});

test('store', function () {
    $this->actingAs($user = User::factory()->create());

    $expectedName = 'Test';

    // here we test if the unique rule is only applied to a specific user's categories
    Category::factory()->state(['name' => $expectedName]);

    $response = $this->post(route('categories.store'), ['name' => $expectedName]);

    $response->assertRedirect(route('categories.index'));
    static::assertSame(1, $user->categories()->count());
    static::assertSame($user->id, $user->categories()->first()->user_id);
    static::assertSame($expectedName, $user->categories()->first()->name);
});

test('store validation fails due to duplicate name', function () {
    $this->actingAs($user = User::factory()->has(Category::factory()->state(['name' => 'Test']))->create());

    $expectedName = 'Test';

    $this->get(route('categories.create'));
    $response = $this->post(route('categories.store'), ['name' => $expectedName]);

    $response->assertRedirect(route('categories.create'));
    $response->assertSessionHasErrors(['name' => 'The Name has already been taken.']);
    static::assertSame(1, $user->categories()->count());
});

test('store validation fails due to missing data', function () {
    $this->actingAs($user = User::factory()->create());

    $this->get(route('categories.create'));
    $response = $this->post(route('categories.store'), ['name' => ' ']);

    $response->assertRedirect(route('categories.create'));
    $response->assertSessionHasErrors(['name' => 'The Name field is required.']);
    static::assertSame(0, $user->categories()->count());
});

test('edit', function () {
    $this->actingAs($user = User::factory()->create());

    $category = Category::factory()->for($user)->create();

    $this->get(route('categories.edit', $category))
        ->assertInertia(fn (Assert $page) => $page
            ->has('category')
            ->where('canDelete', true)
        );
});

test('cannot edit category of another user', function () {
    $this->actingAs(User::factory()->create());

    $category = Category::factory()->create();

    $this->get(route('categories.edit', $category))
        ->assertForbidden();
});

test('update', function () {
    $this->actingAs($user = User::factory()->create());

    $category = Category::factory()->for($user)->create();

    $expectedName = 'Test (updated)';

    // here we test if the unique rule is only applied to a specific user's categories
    Category::factory()->state(['name' => $expectedName]);

    $response = $this->put(route('categories.update', $category), ['name' => $expectedName]);

    $response->assertRedirect(route('categories.index'));
    static::assertSame($user->id, $user->categories()->first()->user_id);
    static::assertSame($expectedName, $user->categories()->first()->name);
});

test('cannot update category of another user', function () {
    $this->actingAs(User::factory()->create());

    $category = Category::factory()->create();

    $this->put(route('categories.update', $category), ['name' => 'Test (updated)'])
        ->assertForbidden();
});

test('cannot update category due to duplicate name', function () {
    $this->actingAs($user = User::factory()->create());

    Category::factory()->state(['name' => 'Test 1'])->for($user)->create();
    $category = Category::factory()->state(['name' => 'Test 2'])->for($user)->create();

    $this->get(route('categories.edit', $category));

    $response = $this->put(route('categories.update', $category), ['name' => 'Test 1']);

    $response->assertRedirect(route('categories.edit', $category));
    $response->assertSessionHasErrors(['name' => 'The Name has already been taken.']);
});

test('delete', function () {
    $this->actingAs($user = User::factory()->create());

    $category = Category::factory()->for($user)->create();

    $response = $this->delete(route('categories.destroy', $category));

    $response->assertRedirect(route('categories.index'));
    static::assertSame(0, $user->categories()->count());
});

test('cannot delete category of another user', function () {
    $this->actingAs(User::factory()->create());

    $category = Category::factory()->create();

    $this->delete(route('categories.destroy', $category))
        ->assertForbidden();

    static::assertSame(1, Category::count());
});
