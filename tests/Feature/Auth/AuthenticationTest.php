<?php

use App\Models\User;
use App\Providers\AppServiceProvider;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

uses(TestCase::class);
uses(RefreshDatabase::class);

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard'));
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('users can logout', function () {
    $this->actingAs(User::factory()->create());

    $this->post('/logout');

    $this->assertGuest();
});

test('user exceeds rate limiting', function () {
    Event::fake();

    $user = User::factory()->create();

    collect(range(0, 5))->each(function (int $index) use ($user) {
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        if ($index === 5) {
            Event::assertDispatched(Lockout::class);
            $response->assertSessionHasErrors(['email']);
        }
    });

    $this->assertGuest();
});

test('redirect if authenticated', function () {
    $this->actingAs(User::factory()->create());

    $response = $this->get('/register');

    $response->assertRedirect(AppServiceProvider::HOME);
});
