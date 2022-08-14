<?php

namespace ApiController;

use App\Models\User;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    public function test_health_check()
    {
        $response = $this->getJson(route('api.v1.health_check'));

        static::assertTrue($response->json('status'));
        static::assertTrue($response->json('services.database'));

        $response->assertOk();
    }

    public function test_retrieve_own_user()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->getJson(route('api.v1.user'));

        static::assertEquals($user->name, $response->json('name'));
        static::assertEquals($user->email, $response->json('email'));
        static::assertEquals($user->email_verified_at->toJSON(), $response->json('email_verified_at'));
        static::assertEquals($user->id, $response->json('id'));
        static::assertEquals($user->profile_photo_url, $response->json('profile_photo_url'));

        $response->assertOk();
    }

    public function test_requires_authorization()
    {
        $response = $this->getJson(route('api.v1.user'));
        $response->assertUnauthorized();
    }

}
