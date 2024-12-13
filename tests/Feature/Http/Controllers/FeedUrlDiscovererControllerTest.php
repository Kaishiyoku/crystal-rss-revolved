<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);

test('returns discovered feed urls', function () {
    actingAs(User::factory()->create());

    post(route('discover-feed-urls'), ['feed_url' => 'https://tailwindcss.com'])
        ->assertJsonIsArray()
        ->assertJsonCount(2);
});
