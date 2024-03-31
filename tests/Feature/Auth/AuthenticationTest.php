<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\AppServiceProvider;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(AppServiceProvider::HOME);
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post('/logout');

        $this->assertGuest();
    }

    public function test_user_exceeds_rate_limiting(): void
    {
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
    }

    public function test_redirect_if_authenticated(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->get('/register');

        $response->assertRedirect(AppServiceProvider::HOME);
    }
}
