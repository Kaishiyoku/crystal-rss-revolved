<?php

namespace Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Admin\UserController;
use App\Models\Category;
use App\Models\Feed;
use App\Models\FeedItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
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

        $this->json('get', route('api.admin.users.index'))
            ->assertJsonStructure([
                'users',
            ])
            ->assertJsonCount(6, 'users');
    }

    public function test_cannot_access_index_as_guest(): void
    {
        $this->json('get', route('api.admin.users.index'))
            ->assertUnauthorized();
    }

    public function test_cannot_access_index_as_normal_user(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get(route('api.admin.users.index'))
            ->assertForbidden();
    }

    public function test_delete(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $user = User::factory()
            ->hasFeedItems(10)
            ->create();

        $this->json('delete', route('api.admin.users.destroy', $user))
            ->assertJson([]);

        static::assertCount(1, User::get());
        static::assertCount(0, Category::get());
        static::assertCount(0, Feed::get());
        static::assertCount(0, FeedItem::get());
        static::assertNull(User::find($user->id));
    }

    public function test_cannot_delete_own_user(): void
    {
        $this->actingAs($user = User::factory()->admin()->create());

        $this->json('delete', route('api.admin.users.destroy', $user))
            ->assertForbidden();

        static::assertSame(1, User::count());
    }
}
