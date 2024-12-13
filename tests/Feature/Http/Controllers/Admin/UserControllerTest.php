<?php

use App\Http\Controllers\Admin\UserController;
use App\Models\Category;
use App\Models\Feed;
use App\Models\FeedItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

test('middleware is registered', function () {
    $middleware = collect((new UserController)->getMiddleware())
        ->map(fn (array $arr) => Arr::get($arr, 'middleware'))
        ->toArray();

    $expectedMiddleware = [
        'can:viewAny,App\Models\User',
        'can:view,user',
        'can:create,App\Models\User',
        'can:update,user',
        'can:delete,user',
    ];

    expect($middleware)->toHaveCount(5)
        ->and($middleware)->toBe($expectedMiddleware);
});

test('index', function () {
    actingAs(User::factory()->admin()->create());

    User::factory()->count(5)->create();

    get(route('admin.users.index'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Users/Index')
            ->count('users', 6)
        );
});

test('cannot access index as guest', function () {
    get(route('admin.users.index'))
        ->assertRedirect('/login');
});

test('cannot access index as normal user', function () {
    get(route('admin.users.index'))
        ->assertRedirect('/login');
});

test('delete', function () {
    actingAs(User::factory()->admin()->create());

    $user = User::factory()
        ->hasFeedItems(10)
        ->create();

    delete(route('admin.users.destroy', $user))
        ->assertRedirect(route('admin.users.index'));

    expect(User::get())->toHaveCount(1)
        ->and(Category::get())->toHaveCount(0)
        ->and(Feed::get())->toHaveCount(0)
        ->and(FeedItem::get())->toHaveCount(0)
        ->and(User::find($user->id))->toBeNull();
});

test('cannot delete own user', function () {
    actingAs($user = User::factory()->admin()->create());

    delete(route('admin.users.destroy', $user))
        ->assertForbidden();

    expect(User::count())->toBe(1);
});
