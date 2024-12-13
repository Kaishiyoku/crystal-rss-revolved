<?php

use App\Http\Requests\DashboardRequest;
use App\Models\Feed;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

uses(TestCase::class);
uses(RefreshDatabase::class);
beforeEach(function () {
    $this->actingAs($user = User::factory()->create());

    Feed::factory()->for($user)->state(['id' => static::FEED_ID])->create();
    Feed::factory()->state(['id' => static::FEED_ID_OF_ANOTHER_USER])->create();
});


test('authorize', function () {
    static::assertTrue((new DashboardRequest)->authorize());
});

test('validate', function (array $data, bool $shouldSucceed, ?string $expectedExceptionMessage = null) {
    $request = new DashboardRequest($data);

    if (! $shouldSucceed) {
        static::expectException(ValidationException::class);
        static::expectExceptionMessage($expectedExceptionMessage);
    }

    $validated = $request->validate($request->rules());

    if ($shouldSucceed) {
        static::assertSame($data, $validated);
    }
})->with('validation');

// Datasets
dataset('validation', [
    'succeeds' => [
        static::makeData(static::FEED_ID),
        true,
    ],
    'feed_id of another user' => [
        static::makeData(static::FEED_ID_OF_ANOTHER_USER),
        false,
        'The selected Feed is invalid',
    ],
]);

// Helpers
function makeData(mixed $feedId): array
{
    return [
        'feed_id' => $feedId,
    ];
}
