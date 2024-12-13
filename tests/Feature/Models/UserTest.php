<?php

use App\Models\Category;
use App\Models\Feed;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

test('verified scope', function () {
    $verifiedUserIds = User::factory(5)->create()->pluck('id');
    User::factory(5)->unverified()->create()->pluck('id');

    static::assertEquals($verifiedUserIds, User::verified()->pluck('id'));
});

test('user has categories', function () {
    $user = User::factory()->create();
    $categories = Category::factory(2)->for($user)->create();

    static::assertEquals(
        $categories->sortBy('name')->pluck('id'),
        $user->categories()->pluck('id')
    );
});

test('user has feeds', function () {
    $user = User::factory()->create();
    $category = Category::factory()->for($user)->create();
    $feeds = Feed::factory(5)->for($user)->for($category)->create();

    static::assertEquals(
        $feeds->sortBy('name')->pluck('id'),
        $user->feeds()->pluck('id')
    );
});
