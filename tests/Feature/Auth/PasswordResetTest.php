<?php

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);

test('reset password link screen can be rendered', function () {
    get('/forgot-password')
        ->assertOk();
});

test('reset password link can be requested', function () {
    Notification::fake();

    $user = User::factory()->create();

    post('/forgot-password', ['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class);
});

test('reset password screen can be rendered', function () {
    Notification::fake();

    $user = User::factory()->create();

    post('/forgot-password', ['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
        get('/reset-password/'.$notification->token)
            ->assertOk();

        return true;
    });
});

test('password can be reset with valid token', function () {
    Notification::fake();

    $user = User::factory()->create();

    post('/forgot-password', ['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
        post('/reset-password', [
            'token' => $notification->token,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ])
            ->assertSessionHasNoErrors();

        return true;
    });
});

test('password cannot be reset with invalid token', function () {
    Event::fake();

    $user = User::factory()->create();

    post('/forgot-password', ['email' => $user->email]);

    $response = post('/reset-password', [
        'token' => 'invalid-token',
        'email' => $user->email,
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    Event::assertNotDispatched(PasswordReset::class);
    $response->assertSessionHasErrors(['email']);
});

test('reset password link cannot be requested for nonexisting user', function () {
    Notification::fake();

    $response = post('/forgot-password', ['email' => 'nonexistent-email@test.dev']);

    Notification::assertNothingSent();
    $response->assertStatus(302);
});
