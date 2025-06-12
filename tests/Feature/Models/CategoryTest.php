<?php

use App\Models\Category;
use App\Models\Feed;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('has traits', function () {
    expect((new ReflectionClass(Category::class))->getTraitNames())->toBe([
        HasFactory::class,
    ]);
});

it('has fillable attributes', function () {
    expect((new ReflectionProperty(Category::factory()->create(), 'fillable'))->getDefaultValue())
        ->toBe([
            'name',
        ]);
});

it('does not cast attributes', function () {
    $category = Category::factory()->create();

    expect((new ReflectionMethod($category, 'casts'))->invoke($category))
        ->toBe([]);
});

it('has no timestamps', function () {
    expect(Category::factory()->create()->timestamps)->toBeFalse();
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
