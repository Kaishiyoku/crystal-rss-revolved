<?php

namespace Tests\Feature\Http\Requests;

use App\Http\Requests\DashboardRequest;
use App\Models\Feed;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class DashboardRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_validation_succeeds(): void
    {
        $user = User::factory()->create();
        $feed = Feed::factory()->for($user)->create();

        $expectedData = ['feed_id' => $feed->id];

        $this->actingAs($user);

        $request = new DashboardRequest($expectedData);
        $validatedData = $request->validate($request->rules());

        static::assertSame($expectedData, $validatedData);
    }

    public function test_non_existing_feed_id(): void
    {
        $this->actingAs(User::factory()->create());

        static::expectException(ValidationException::class);

        $request = new DashboardRequest(['feed_id' => 1]);
        $request->validate($request->rules());
    }

    public function test_feed_id_of_wrong_user(): void
    {
        $this->actingAs(User::factory()->create());

        $anotherUser = User::factory()->create();
        $feedOfAnotherUser = Feed::factory()->for($anotherUser)->create();

        static::expectException(ValidationException::class);

        $request = new DashboardRequest(['feed_id' => $feedOfAnotherUser->id]);
        $request->validate($request->rules());
    }
}
