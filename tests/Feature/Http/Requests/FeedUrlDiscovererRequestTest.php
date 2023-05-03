<?php

namespace Http\Requests;

use App\Http\Requests\FeedDiscovererRequest;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class FeedUrlDiscovererRequestTest extends TestCase
{
    public function test_validation_succeeds(): void
    {
        $expectedData = ['feed_url' => 'https://test.dev'];

        $request = new FeedDiscovererRequest($expectedData);
        $validatedData = $request->validate($request->rules());

        static::assertSame($expectedData, $validatedData);
    }

    public function test_invalid_url(): void
    {
        $expectedData = ['feed_url' => 'test.dev'];

        static::expectException(ValidationException::class);

        $request = new FeedDiscovererRequest($expectedData);
        $request->validate($request->rules());
    }
}
