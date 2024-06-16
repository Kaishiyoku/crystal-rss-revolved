<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->json('patch', route('api.profile.update'), [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ])
            ->assertJson([]);

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->json('patch', route('api.profile.update'), [
                'name' => 'Test User',
                'email' => $user->email,
            ])
            ->assertJson([]);

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->json('delete', route('api.profile.destroy'), [
                'password' => 'password',
            ])
            ->assertJson([]);

        $this->assertGuest('web');
        $this->assertNull($user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->json('delete', route('api.profile.destroy'), [
                'password' => 'wrong-password',
            ])
            ->assertJson([]);

        $this->assertNotNull($user->fresh());
    }
}
