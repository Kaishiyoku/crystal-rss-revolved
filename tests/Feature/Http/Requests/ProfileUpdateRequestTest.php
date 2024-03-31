<?php

namespace Http\Requests;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ProfileUpdateRequestTest extends TestCase
{
    use RefreshDatabase;

    private const USER_EMAIL = 'test@test.dev';

    private const USER_EMAIL_OF_ANOTHER_USER = 'test2@test.dev';

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs($this->user = User::factory()->state(['email' => static::USER_EMAIL])->create());
        User::factory()->state(['email' => static::USER_EMAIL_OF_ANOTHER_USER])->create();
    }

    public function test_authorize(): void
    {
        static::assertTrue((new ProfileUpdateRequest())->authorize());
    }

    /**
     * @dataProvider validationDataProvider
     */
    public function test_validate(array $data, bool $shouldSucceed, ?string $expectedExceptionMessage = null): void
    {
        $request = new ProfileUpdateRequest($data);
        $request->setUserResolver(fn () => $this->user);

        if (! $shouldSucceed) {
            static::expectException(ValidationException::class);
            static::expectExceptionMessage($expectedExceptionMessage);
        }

        $validated = $request->validate($request->rules());

        if ($shouldSucceed) {
            static::assertSame($data, $validated);
        }
    }

    public static function validationDataProvider(): array
    {
        return [
            'succeeds' => [
                static::makeData(),
                true,
            ],
            'invalid name' => [
                static::makeData(name: 123),
                false,
                'The Name field must be a string.',
            ],
            'overly long name' => [
                static::makeData(name: Str::random(256)),
                false,
                'The Name field must not be greater than 255 characters.',
            ],
            'invalid email' => [
                static::makeData(email: 'test'),
                false,
                'The email field must be a valid email address.',
            ],
            'overly long email' => [
                static::makeData(email: Str::random(248).'@test.de'),
                false,
                'The email field must not be greater than 255 characters.',
            ],
            'email of own user' => [
                static::makeData(email: static::USER_EMAIL),
                true,
            ],
            'email of another user' => [
                static::makeData(email: static::USER_EMAIL_OF_ANOTHER_USER),
                false,
                'The email has already been taken.',
            ],
        ];
    }

    private static function makeData(mixed $name = null, mixed $email = null): array
    {
        return [
            'name' => $name ?? 'Test',
            'email' => $email ?? 'test@test.de',
        ];
    }
}
