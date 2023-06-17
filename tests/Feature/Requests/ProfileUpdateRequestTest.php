<?php

namespace Requests;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
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
     * @dataProvider rulesDataProvider
     */
    public function test_rules(array $request, bool $shouldSucceed, string $expectedExceptionMessage = null): void
    {
        $profileUpdateRequest = new ProfileUpdateRequest([], $request);
        $profileUpdateRequest->setUserResolver(fn () => $this->user);

        if (! $shouldSucceed) {
            static::expectException(ValidationException::class);
            static::expectExceptionMessage($expectedExceptionMessage);
        }

        $validated = Validator::validate($request, $profileUpdateRequest->rules());

        if ($shouldSucceed) {
            static::assertSame($request, $validated);
        }
    }

    public static function rulesDataProvider(): array
    {
        return [
            'succeeds' => [
                static::makeRequest(),
                true,
            ],
            'invalid name' => [
                static::makeRequest(name: 123),
                false,
                'The Name must be a string.',
            ],
            'overly long name' => [
                static::makeRequest(name: Str::random(256)),
                false,
                'The Name may not be greater than 255 characters.',
            ],
            'invalid email' => [
                static::makeRequest(email: 'test'),
                false,
                'The email must be a valid email address.',
            ],
            'overly long email' => [
                static::makeRequest(email: Str::random(248).'@test.de'),
                false,
                'The email may not be greater than 255 characters.',
            ],
            'email of own user' => [
                static::makeRequest(email: static::USER_EMAIL),
                true,
            ],
            'email of another user' => [
                static::makeRequest(email: static::USER_EMAIL_OF_ANOTHER_USER),
                false,
                'The email has already been taken.',
            ],
        ];
    }

    private static function makeRequest(mixed $name = null, mixed $email = null): array
    {
        return [
            'name' => $name ?? 'Test',
            'email' => $email ?? 'test@test.de',
        ];
    }
}
