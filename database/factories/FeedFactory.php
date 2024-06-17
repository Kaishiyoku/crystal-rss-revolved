<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Feed>
 */
class FeedFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'feed_url' => fake()->url(),
            'site_url' => fake()->url(),
            'favicon_url' => fake()->optional()->url(),
            'name' => fake()->text(),
            'language' => fake()->languageCode(),
            'last_checked_at' => fake()->optional()->dateTime(),
            'last_failed_at' => fake()->optional()->dateTime(),
        ];
    }
}
