<?php

use App\Models\Category;
use App\Models\Feed;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

uses(RefreshDatabase::class);

it('has traits', function () {
    expect((new ReflectionClass(User::class))->getTraitNames())->toBe([
        HasApiTokens::class,
        HasFactory::class,
        Notifiable::class,
    ]);
});

it('has fillable attributes', function () {
    expect((new ReflectionProperty(User::factory()->create(), 'fillable'))->getDefaultValue())
        ->toBe([
            'name',
            'email',
            'password',
        ]);
});

it('has hidden attributes', function () {
    expect((new ReflectionProperty(User::factory()->create(), 'hidden'))->getDefaultValue())
        ->toBe([
            'password',
            'remember_token',
        ]);
});

it('casts attributes', function () {
    $user = User::factory()->create();

    expect((new ReflectionMethod($user, 'casts'))->invoke($user))
        ->toBe([
            'is_admin' => 'bool',
            'email_verified_at' => 'datetime',
        ]);
});

it('has interfaces', function () {
    expect(User::factory()->create()->interfaces)->toBe([
        'tokens' => [
            'type' => 'unknown',
        ],
        'notifications' => [
            'type' => 'unknown',
        ],
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
