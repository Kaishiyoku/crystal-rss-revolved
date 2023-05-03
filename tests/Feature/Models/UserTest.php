<?php

namespace Models;

use App\Models\Category;
use App\Models\Feed;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_verified_scope(): void
    {
        $verifiedUserIds = User::factory(5)->create()->pluck('id');
        User::factory(5)->unverified()->create()->pluck('id');

        static::assertEquals($verifiedUserIds, User::verified()->pluck('id'));
    }

    public function test_user_has_categories(): void
    {
        $user = User::factory()->create();
        $categories = Category::factory(2)->for($user)->create();

        static::assertEquals(
            $categories->sortBy('name')->pluck('id', 'name'),
            $user->categories()->pluck('id', 'name')
        );
    }

    public function test_user_has_feeds(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->for($user)->create();
        $feeds = Feed::factory(5)->for($user)->for($category)->create();

        static::assertEquals(
            $feeds->sortBy('name')->pluck('id', 'name'),
            $user->feeds()->pluck('id', 'name')
        );
    }
}
