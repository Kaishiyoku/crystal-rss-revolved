<?php

use App\Models\Category;
use App\Models\Feed;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('has fillable attributes', function () {
    $property = new ReflectionProperty(Category::class, 'fillable');

    expect($property->getValue(new Category()))->toBe([
        'name',
    ]);
});

test('category belongs to user', function () {
    $user = User::factory()->create();
    $category = Category::factory()->for($user)->create();

    expect($category->user->id)->toBe($user->id);
});

test('category has feeds', function () {
    $user = User::factory()->create();
    $category = Category::factory()->for($user)->create();
    $feeds = Feed::factory(5)->for($user)->for($category)->create();

    expect($category->feeds()->pluck('id'))->toEqual($feeds->sortBy('name')->pluck('id'));
});
