<?php

namespace Http\Middleware;

use App\Http\Middleware\Administrate;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class AdministrateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider middlewareDataProvider
     */
    public function test_middleware(?UserFactory $userFactory, bool $expectVisited): void
    {
        $user = $userFactory?->create();

        if ($userFactory) {
            $this->actingAs($user);
        }

        $visited = false;
        $middleware = new Administrate();

        try {
            $middleware->handle(Request::create('/telescope')->setUserResolver(fn () => $user ?? null), function () use (&$visited) {
                $visited = true;

                return Response::make();
            });
        } catch (HttpException) {
            //
        }

        static::assertSame($expectVisited, $visited);
    }

    public static function middlewareDataProvider(): array
    {
        return [
            'administrator' => [
                User::factory()->admin(),
                true,
            ],
            'not an administrator' => [
                User::factory(),
                false,
            ],
            'not logged in' => [
                null,
                false,
            ],
        ];
    }
}
