<?php

namespace Http\Controllers\Admin;

use App\Http\Controllers\Admin\UserController;
use App\Models\Category;
use App\Models\Feed;
use App\Models\FeedItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_middleware_is_registered(): void
    {
        $middleware = collect((new UserController())->getMiddleware())
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
    }

    public function test_index(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        User::factory()->count(5)->create();

        $this->get(route('admin.users.index'))
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Users/Index')
                ->count('users', 6)
            );
    }

    public function test_cannot_access_index_as_guest(): void
    {
        $this->get(route('admin.users.index'))
            ->assertRedirect('/login');
    }

    public function test_cannot_access_index_as_normal_user(): void
    {
        $this->get(route('admin.users.index'))
            ->assertRedirect('/login');
    }

    public function test_delete(): void
    {
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
    }

    public function test_cannot_delete_own_user(): void
    {
        $this->actingAs($user = User::factory()->admin()->create());

        $this->delete(route('admin.users.destroy', $user))
            ->assertForbidden();

        static::assertSame(1, User::count());
    }
}
