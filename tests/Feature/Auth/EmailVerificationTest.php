<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_verification_screen_can_be_rendered(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->get('/verify-email');

        $response->assertStatus(200);
    }

    public function test_email_can_be_verified(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        Event::fake();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        Event::assertDispatched(Verified::class);
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $response->assertRedirect(RouteServiceProvider::HOME.'?verified=1');
    }

    public function test_email_is_not_verified_with_invalid_hash(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1('wrong-email')]
        );

        $this->actingAs($user)->get($verificationUrl);

        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    public function test_email_has_already_been_verified(): void
    {
        $response = $this->actingAs(User::factory()->create())->get('/verify-email');

        $response->assertStatus(302);
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_email_verification_notification_has_been_sent(): void
    {
        $user = User::factory()->unverified()->create();

        Notification::fake();

        $this->actingAs($user);

        $this->get('/profile');

        $response = $this->post('/email/verification-notification');

        Notification::assertSentTo($user, VerifyEmail::class);
        $response->assertStatus(302);
        $response->assertRedirect('/profile');
    }

    public function test_email_verification_notification_has_not_been_sent_for_already_verified_email(): void
    {
        $user = User::factory()->create();

        Notification::fake();

        $this->actingAs($user);

        $this->get('/profile');

        $response = $this->post('/email/verification-notification');

        Notification::assertNotSentTo($user, VerifyEmail::class);
        $response->assertStatus(302);
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_email_cannot_be_verified_because_it_already_is(): void
    {
        $user = User::factory()->create();

        Event::fake();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        Event::assertNotDispatched(Verified::class);
        $response->assertRedirect(RouteServiceProvider::HOME.'?verified=1');
    }
}
