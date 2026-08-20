<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertGuest;

uses(RefreshDatabase::class);

it('should render the welcome page for guests', function () {
    assertGuest()
        ->get(route('welcome'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Welcome')
            ->where('canLogin', true)
            ->where('canRegister', true)
            ->has('contactEmail')
            ->has('githubUrl')
        );
});

it('should redirect to the dashboard page if already logged in', function () {
    actingAs(User::factory()->create())
        ->get(route('welcome'))
        ->assertRedirectToRoute('dashboard');
});
