<?php

use App\Http\Middleware\Administrate;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

test('middleware', function (?UserFactory $userFactory, bool $expectVisited) {
    $user = $userFactory?->create();

    if ($userFactory) {
        actingAs($user);
    }

    $visited = false;
    $middleware = new Administrate;
    $middleware->handle(Request::create('/users')->setUserResolver(fn () => $user ?? null), function () use (&$visited) {
        $visited = true;

        return Response::make();
    });

    expect($visited)->toBe($expectVisited);
})->with('middleware');

// Datasets
dataset('middleware', [
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
]);
