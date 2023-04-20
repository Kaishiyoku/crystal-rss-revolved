<?php

namespace Tests\Feature\Providers;

use App\Models\User;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class TelescopeServiceProviderTest extends TestCase
{
    public function test_cannot_access_telescope_as_guest(): void
    {
        $response = $this->get(route('telescope'));

        $response->assertForbidden();
    }

    public function test_cannot_access_telescope_as_normal_user(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->get(route('telescope'));

        $response->assertForbidden();
    }

    public function test_cannot_access_telescope_as_admin_user(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $response = $this->get(route('telescope'));

        $response->assertOk();
    }
}
