<?php

namespace Tests\Feature\Models;

use App\Models\Category;
use App\Models\Feed;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->for($user)->create();

        static::assertSame($user->id, $category->user->id);
    }

    public function test_category_has_feeds(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->for($user)->create();
        $feeds = Feed::factory(5)->for($user)->for($category)->create();

        static::assertEquals(
            $feeds->sortBy('name')->pluck('id'),
            $category->feeds()->pluck('id'),
        );
    }
}
