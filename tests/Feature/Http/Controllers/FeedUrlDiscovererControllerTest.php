<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

test('returns discovered feed urls', function () {
    $this->actingAs(User::factory()->create());

    $this->post(route('discover-feed-urls'), ['feed_url' => 'https://tailwindcss.com'])
        ->assertJsonIsArray()
        ->assertJsonCount(2);
});
