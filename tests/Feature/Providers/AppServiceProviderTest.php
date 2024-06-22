<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertGuest;

it('doesn\'t render the Laravel Pulse page for guests', function () {
    assertGuest()
        ->get(route('pulse'))
        ->assertForbidden();
});

it('doesn\'t render the Laravel Pulse page for normal users', function () {
    actingAs(User::factory()->create())
        ->get(route('pulse'))
        ->assertForbidden();
});

it('render the Laravel Pulse page for admin users', function () {
    actingAs(User::factory()->admin()->create())
        ->get(route('pulse'))
        ->assertOk();
});
