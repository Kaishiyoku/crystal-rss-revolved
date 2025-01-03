<?php

use App\Models\User;
use App\Providers\AppServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);

test('email verification screen can be rendered', function () {
    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    actingAs($user)->get('/verify-email')
        ->assertOk();
});

test('email can be verified', function () {
    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    Event::fake();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1($user->email)]
    );

    $response = actingAs($user)->get($verificationUrl);

    Event::assertDispatched(Verified::class);
    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
    $response->assertRedirect(AppServiceProvider::HOME.'?verified=1');
});

test('email is not verified with invalid hash', function () {
    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1('wrong-email')]
    );

    actingAs($user)->get($verificationUrl);

    expect($user->fresh()->hasVerifiedEmail())->toBeFalse();
});

test('email has already been verified', function () {
    $response = actingAs(User::factory()->create())->get('/verify-email');

    $response->assertStatus(302);
    $response->assertRedirect(AppServiceProvider::HOME);
});

test('email verification notification has been sent', function () {
    $user = User::factory()->unverified()->create();

    Notification::fake();

    actingAs($user);

    get('/profile');

    $response = post('/email/verification-notification');

    Notification::assertSentTo($user, VerifyEmail::class);
    $response->assertStatus(302);
    $response->assertRedirect('/profile');
});

test('email verification notification has not been sent for already verified email', function () {
    $user = User::factory()->create();

    Notification::fake();

    actingAs($user);

    get('/profile');

    $response = post('/email/verification-notification');

    Notification::assertNotSentTo($user, VerifyEmail::class);
    $response->assertStatus(302);
    $response->assertRedirect(AppServiceProvider::HOME);
});

test('email cannot be verified because it already is', function () {
    $user = User::factory()->create();

    Event::fake();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1($user->email)]
    );

    $response = actingAs($user)->get($verificationUrl);

    Event::assertNotDispatched(Verified::class);
    $response->assertRedirect(AppServiceProvider::HOME.'?verified=1');
});
