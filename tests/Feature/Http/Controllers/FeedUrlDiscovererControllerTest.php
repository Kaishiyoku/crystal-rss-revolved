<?php

namespace Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeedUrlDiscovererControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_discovered_feed_urls(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('discover-feed-urls'), ['feed_url' => 'https://tailwindcss.com'])
            ->assertJsonIsArray()
            ->assertJsonCount(2);
    }
}
