<?php

use App\Models\Category;
use App\Models\Feed;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('has fillable attributes', function () {
    $property = new ReflectionProperty(User::class, 'fillable');

    expect($property->getValue(new User()))->toBe([
        'name',
        'email',
        'password',
    ]);
});

it('has hidden attributes', function () {
    $property = new ReflectionProperty(User::class, 'hidden');

    expect($property->getValue(new User()))->toBe([
        'password',
        'remember_token',
    ]);
});

test('verified scope', function () {
    $verifiedUserIds = User::factory(5)->create()->pluck('id');
    User::factory(5)->unverified()->create()->pluck('id');

    expect(User::verified()->pluck('id'))->toEqual($verifiedUserIds);
});

test('user has categories', function () {
    $user = User::factory()->create();
    $categories = Category::factory(2)->for($user)->create();

    expect($user->categories()->pluck('id'))->toEqual($categories->sortBy('name')->pluck('id'));
});

test('user has feeds', function () {
    $user = User::factory()->create();
    $category = Category::factory()->for($user)->create();
    $feeds = Feed::factory(5)->for($user)->for($category)->create();

    expect($user->feeds()->pluck('id'))->toEqual($feeds->sortBy('name')->pluck('id'));
});
