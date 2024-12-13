<?php

use App\Http\Requests\DashboardRequest;
use App\Models\Feed;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

use function Pest\Laravel\actingAs;

// Helpers
$makeData = fn (mixed $feedId): array => [
    'feed_id' => $feedId,
];

uses(RefreshDatabase::class);
beforeEach(function () {
    actingAs($user = User::factory()->create());

    Feed::factory()->for($user)->state(['id' => fakeFeedId()])->create();
    Feed::factory()->state(['id' => fakeFeedIdOfAnotherUser()])->create();
});

test('authorize', function () {
    expect((new DashboardRequest)->authorize())->toBeTrue();
});

test('validate', function (array $data, bool $shouldSucceed, ?string $expectedExceptionMessage = null) {
    $request = new DashboardRequest($data);

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
        $makeData(fakeFeedId()),
        true,
    ],
    'feed_id of another user' => [
        $makeData(fakeFeedIdOfAnotherUser()),
        false,
        'The selected Feed is invalid',
    ],
]);
