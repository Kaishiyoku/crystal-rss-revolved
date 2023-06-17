<?php

namespace Http\Requests;

use App\Http\Requests\DashboardRequest;
use App\Models\Feed;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class DashboardRequestTest extends TestCase
{
    use RefreshDatabase;

    private const FEED_ID = 1;

    private const FEED_ID_OF_ANOTHER_USER = 2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs($user = User::factory()->create());

        Feed::factory()->for($user)->state(['id' => static::FEED_ID])->create();
        Feed::factory()->state(['id' => static::FEED_ID_OF_ANOTHER_USER])->create();
    }

    public function test_authorize(): void
    {
        static::assertTrue((new DashboardRequest())->authorize());
    }

    /**
     * @dataProvider validationDataProvider
     */
    public function test_validate(array $data, bool $shouldSucceed, string $expectedExceptionMessage = null): void
    {
        $request = new DashboardRequest($data);

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
                static::makeData(static::FEED_ID),
                true,
            ],
            'feed_id of another user' => [
                static::makeData(static::FEED_ID_OF_ANOTHER_USER),
                false,
                'The selected Feed is invalid',
            ],
        ];
    }

    private static function makeData(mixed $feedId): array
    {
        return [
            'feed_id' => $feedId,
        ];
    }
}
