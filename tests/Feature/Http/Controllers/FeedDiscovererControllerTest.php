<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);

test('success', function () {
    actingAs(User::factory()->create());

    post(route('discover-feed'), ['feed_url' => 'https://tailwindcss.com/feeds/feed.xml'])
        ->assertJsonIsObject()
        ->assertJsonStructure([
            'feed_url',
            'site_url',
            'favicon_url',
            'name',
            'language',
        ]);
});

test('no feed found', function () {
    actingAs(User::factory()->create());

    $response = post(route('discover-feed'), ['feed_url' => 'https://blurha.sh/']);

    $response->assertNotFound();
    expect($response->exception->getMessage())->toBe('No feeds found.');
});

test('cannot access as guest', function () {
    post(route('discover-feed'))
        ->assertRedirect('/login');
});

test('connect exception', function () {
    actingAs(User::factory()->create());

    $response = post(route('discover-feed'), ['feed_url' => 'https://test.dev']);

    $response->assertUnprocessable();
    expect($response->exception->getMessage())->toBe('The given URL is invalid.');
});

test('client exception', function () {
    actingAs(User::factory()->create());

    $response = post(route('discover-feed'), ['feed_url' => 'https://tailwindcss.com/random-page']);

    $response->assertUnprocessable();
    expect($response->exception->getMessage())->toBe('The given URL could not be resolved.');
});
