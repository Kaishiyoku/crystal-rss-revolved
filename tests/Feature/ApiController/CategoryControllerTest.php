<?php

namespace ApiController;

use App\Models\Category;
use App\Models\User;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_retrieves_resources()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->getJson(route('api.v1.categories.index'));

        // since we haven't added any categories the response should be empty
        static::assertEmpty($response->json());

        // add a category and check that it is returned in the response
        $category = $user->categories()->save(Category::factory()->make());

        $response = $this->getJson(route('api.v1.categories.index'));

        static::assertNotEmpty($response->json());
        static::assertIsArray($response->json());
        static::assertCount(1, $response->json());
        static::assertEquals($category->user_id, $response->json('0.user_id'));
        static::assertEquals($category->name, $response->json('0.name'));

        $response->assertOk();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_creates_resource()
    {
        $categoryName = 'Test Category';

        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->postJson(route('api.v1.categories.store'), ['name' => $categoryName]);

        // one category should be created
        static::assertCount(1, $user->categories);
        static::assertEquals($categoryName, $user->categories->first()->name);

        $response->assertOk();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_updates_resource()
    {
        $categoryName = 'Updated Test Category';

        $user = User::factory()->create();
        $category = $user->categories()->save(Category::factory()->make());

        $this->actingAs($user);

        $response = $this->putJson(route('api.v1.categories.update', $category), ['name' => $categoryName]);

        // one category should be created
        static::assertCount(1, $user->categories);
        static::assertEquals($categoryName, $user->categories->first()->name);

        $response->assertOk();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_deletes_resource()
    {
        $user = User::factory()->create();
        $category = $user->categories()->save(Category::factory()->make());

        $this->actingAs($user);

        $response = $this->deleteJson(route('api.v1.categories.destroy', $category));

        // there should be no categories
        static::assertEmpty($user->categories);

        $response->assertOk();
    }
}
