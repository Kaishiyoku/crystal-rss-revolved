<?php

namespace Requests;

use App\Http\Requests\DashboardRequest;
use App\Http\Requests\StoreFeedRequest;
use App\Models\Feed;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
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
        static::assertTrue((new StoreFeedRequest())->authorize());
    }

    /**
     * @dataProvider rulesDataProvider
     */
    public function test_rules(array $request, bool $shouldSucceed, string $expectedExceptionMessage = null): void
    {
        $dashboardRequest = new DashboardRequest($request);

        if (! $shouldSucceed) {
            static::expectException(ValidationException::class);
            static::expectExceptionMessage($expectedExceptionMessage);
        }

        $validated = Validator::validate($request, $dashboardRequest->rules());

        if ($shouldSucceed) {
            static::assertSame($request, $validated);
        }
    }

    public static function rulesDataProvider(): array
    {
        return [
            'succeeds' => [
                static::makeRequest(static::FEED_ID),
                true,
            ],
            'feed_id of another user' => [
                static::makeRequest(static::FEED_ID_OF_ANOTHER_USER),
                false,
                'The selected Feed is invalid',
            ],
        ];
    }

    private static function makeRequest(mixed $feedId): array {
        return [
            'feed_id' => $feedId,
        ];
    }
}
