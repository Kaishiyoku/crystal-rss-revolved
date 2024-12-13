<?php

use App\Models\Category;
use App\Models\Feed;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class);
uses(RefreshDatabase::class);

test('category belongs to user', function () {
    $user = User::factory()->create();
    $category = Category::factory()->for($user)->create();

    static::assertSame($user->id, $category->user->id);
});

test('category has feeds', function () {
    $user = User::factory()->create();
    $category = Category::factory()->for($user)->create();
    $feeds = Feed::factory(5)->for($user)->for($category)->create();

    static::assertEquals(
        $feeds->sortBy('name')->pluck('id'),
        $category->feeds()->pluck('id'),
    );
});
