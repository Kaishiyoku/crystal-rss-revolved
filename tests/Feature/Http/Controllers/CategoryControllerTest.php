<?php

use App\Http\Controllers\CategoryController;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

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

    expect($middleware)->toHaveCount(5)
        ->and($middleware)->toBe($expectedMiddleware);
});

test('index', function () {
    actingAs($user = User::factory()->create());

    Category::factory()->for($user)->create();

    get(route('categories.index'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Categories/Index')
            ->count('categories', 1)
            ->where('canCreate', true)
        );
});

test('cannot access index as guest', function () {
    get(route('categories.index'))
        ->assertRedirect('/login');
});

test('create', function () {
    actingAs(User::factory()->create());

    get(route('categories.create'))
        ->assertInertia(fn (Assert $page) => $page
            ->has('category')
        );
});

test('store', function () {
    actingAs($user = User::factory()->create());

    $expectedName = 'Test';

    // here we test if the unique rule is only applied to a specific user's categories
    Category::factory()->state(['name' => $expectedName]);

    $response = post(route('categories.store'), ['name' => $expectedName]);

    $response->assertRedirect(route('categories.index'));
    expect($user->categories()->count())->toBe(1)
        ->and($user->categories()->first()->user_id)->toBe($user->id)
        ->and($user->categories()->first()->name)->toBe($expectedName);
});

test('store validation fails due to duplicate name', function () {
    actingAs($user = User::factory()->has(Category::factory()->state(['name' => 'Test']))->create());

    $expectedName = 'Test';

    get(route('categories.create'));
    $response = post(route('categories.store'), ['name' => $expectedName]);

    $response->assertRedirect(route('categories.create'));
    $response->assertSessionHasErrors(['name' => 'The Name has already been taken.']);
    expect($user->categories()->count())->toBe(1);
});

test('store validation fails due to missing data', function () {
    actingAs($user = User::factory()->create());

    get(route('categories.create'));
    $response = post(route('categories.store'), ['name' => ' ']);

    $response->assertRedirect(route('categories.create'));
    $response->assertSessionHasErrors(['name' => 'The Name field is required.']);
    expect($user->categories()->count())->toBe(0);
});

test('edit', function () {
    actingAs($user = User::factory()->create());

    $category = Category::factory()->for($user)->create();

    get(route('categories.edit', $category))
        ->assertInertia(fn (Assert $page) => $page
            ->has('category')
            ->where('canDelete', true)
        );
});

test('cannot edit category of another user', function () {
    actingAs(User::factory()->create());

    $category = Category::factory()->create();

    get(route('categories.edit', $category))
        ->assertForbidden();
});

test('update', function () {
    actingAs($user = User::factory()->create());

    $category = Category::factory()->for($user)->create();

    $expectedName = 'Test (updated)';

    // here we test if the unique rule is only applied to a specific user's categories
    Category::factory()->state(['name' => $expectedName]);

    $response = put(route('categories.update', $category), ['name' => $expectedName]);

    $response->assertRedirect(route('categories.index'));
    expect($user->categories()->first()->user_id)->toBe($user->id)
        ->and($user->categories()->first()->name)->toBe($expectedName);
});

test('cannot update category of another user', function () {
    actingAs(User::factory()->create());

    $category = Category::factory()->create();

    put(route('categories.update', $category), ['name' => 'Test (updated)'])
        ->assertForbidden();
});

test('cannot update category due to duplicate name', function () {
    actingAs($user = User::factory()->create());

    Category::factory()->state(['name' => 'Test 1'])->for($user)->create();
    $category = Category::factory()->state(['name' => 'Test 2'])->for($user)->create();

    get(route('categories.edit', $category));

    $response = put(route('categories.update', $category), ['name' => 'Test 1']);

    $response->assertRedirect(route('categories.edit', $category));
    $response->assertSessionHasErrors(['name' => 'The Name has already been taken.']);
});

test('delete', function () {
    actingAs($user = User::factory()->create());

    $category = Category::factory()->for($user)->create();

    $response = delete(route('categories.destroy', $category));

    $response->assertRedirect(route('categories.index'));
    expect($user->categories()->count())->toBe(0);
});

test('cannot delete category of another user', function () {
    actingAs(User::factory()->create());

    $category = Category::factory()->create();

    delete(route('categories.destroy', $category))
        ->assertForbidden();

    expect(Category::count())->toBe(1);
});
