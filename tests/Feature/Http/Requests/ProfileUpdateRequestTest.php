<?php

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

use function Pest\Laravel\actingAs;

// Helpers
$makeData = fn (mixed $name = null, mixed $email = null): array => [
    'name' => $name ?? 'Test',
    'email' => $email ?? 'test@test.de',
];

uses(RefreshDatabase::class);
beforeEach(function () {
    actingAs($this->user = User::factory()->state(['email' => fakeUserEmail()])->create());
    User::factory()->state(['email' => fakeUserEmailOfAnotherUser()])->create();
});

test('authorize', function () {
    expect((new ProfileUpdateRequest)->authorize())->toBeTrue();
});

test('validate', function (array $data, bool $shouldSucceed, ?string $expectedExceptionMessage = null) {
    $request = new ProfileUpdateRequest($data);
    $request->setUserResolver(fn () => $this->user);

    if (! $shouldSucceed) {
        expect(fn () => $request->validate($request->rules()))
            ->toThrow(ValidationException::class, $expectedExceptionMessage);
    } else {
        $validated = $request->validate($request->rules());

        expect($validated)->toBe($data);
    }
})->with('validation');

// Datasets
dataset('validation', [
    'succeeds' => [
        $makeData(),
        true,
    ],
    'invalid name' => [
        $makeData(name: 123),
        false,
        'The Name field must be a string.',
    ],
    'overly long name' => [
        $makeData(name: Str::random(256)),
        false,
        'The Name field must not be greater than 255 characters.',
    ],
    'invalid email' => [
        $makeData(email: 'test'),
        false,
        'The email field must be a valid email address.',
    ],
    'overly long email' => [
        $makeData(email: Str::random(248).'@test.de'),
        false,
        'The email field must not be greater than 255 characters.',
    ],
    'email of own user' => [
        $makeData(email: fakeUserEmail()),
        true,
    ],
    'email of another user' => [
        $makeData(email: fakeUserEmailOfAnotherUser()),
        false,
        'The email has already been taken.',
    ],
]);
