<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class);
uses(RefreshDatabase::class);

test('success', function () {
    $this->actingAs(User::factory()->create());

    $this->post(route('discover-feed'), ['feed_url' => 'https://tailwindcss.com/feeds/feed.xml'])
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
    $this->actingAs(User::factory()->create());

    $response = $this->post(route('discover-feed'), ['feed_url' => 'https://blurha.sh/']);

    $response->assertNotFound();
    static::assertSame('No feeds found.', $response->exception->getMessage());
});

test('cannot access as guest', function () {
    $this->post(route('discover-feed'))
        ->assertRedirect('/login');
});

test('connect exception', function () {
    $this->actingAs(User::factory()->create());

    $response = $this->post(route('discover-feed'), ['feed_url' => 'https://test.dev']);

    $response->assertUnprocessable();
    static::assertSame('The given URL is invalid.', $response->exception->getMessage());
});

test('client exception', function () {
    $this->actingAs(User::factory()->create());

    $response = $this->post(route('discover-feed'), ['feed_url' => 'https://tailwindcss.com/random-page']);

    $response->assertUnprocessable();
    static::assertSame('The given URL could not be resolved.', $response->exception->getMessage());
});
