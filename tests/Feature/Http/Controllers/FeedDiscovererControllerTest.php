<?php

namespace Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeedDiscovererControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_success(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('discover-feed'), ['feed_url' => 'https://tailwindcss.com'])
            ->assertJsonIsObject()
            ->assertJsonStructure([
                'feed_url',
                'site_url',
                'favicon_url',
                'name',
                'language',
            ]);
    }

    public function test_no_feed_found(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->post(route('discover-feed'), ['feed_url' => 'https://v2.wttr.in/']);

        $response->assertNotFound();
        static::assertSame('No feeds found.', $response->exception->getMessage());
    }

    public function test_cannot_access_as_guest(): void
    {
        $this->post(route('discover-feed'))
            ->assertRedirect('/login');
    }

    public function test_connect_exception(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->post(route('discover-feed'), ['feed_url' => 'https://test.dev']);

        $response->assertUnprocessable();
        static::assertSame('The given URL is invalid.', $response->exception->getMessage());
    }

    public function test_client_exception(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->post(route('discover-feed'), ['feed_url' => 'https://tailwindcss.com/random-page']);

        $response->assertUnprocessable();
        static::assertSame('The given URL could not be resolved.', $response->exception->getMessage());
    }
}
