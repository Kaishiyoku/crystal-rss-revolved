<?php

use App\Http\Controllers\Admin\UserController;
use App\Models\Category;
use App\Models\Feed;
use App\Models\FeedItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

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

    static::assertCount(5, $middleware);
    static::assertSame($expectedMiddleware, $middleware);
});

test('index', function () {
    $this->actingAs(User::factory()->admin()->create());

    User::factory()->count(5)->create();

    $this->get(route('admin.users.index'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Users/Index')
            ->count('users', 6)
        );
});

test('cannot access index as guest', function () {
    $this->get(route('admin.users.index'))
        ->assertRedirect('/login');
});

test('cannot access index as normal user', function () {
    $this->get(route('admin.users.index'))
        ->assertRedirect('/login');
});

test('delete', function () {
    $this->actingAs(User::factory()->admin()->create());

    $user = User::factory()
        ->hasFeedItems(10)
        ->create();

    $this->delete(route('admin.users.destroy', $user))
        ->assertRedirect(route('admin.users.index'));

    static::assertCount(1, User::get());
    static::assertCount(0, Category::get());
    static::assertCount(0, Feed::get());
    static::assertCount(0, FeedItem::get());
    static::assertNull(User::find($user->id));
});

test('cannot delete own user', function () {
    $this->actingAs($user = User::factory()->admin()->create());

    $this->delete(route('admin.users.destroy', $user))
        ->assertForbidden();

    static::assertSame(1, User::count());
});
