<?php

namespace Database\Factories;

use App\Models\Feed;
use Illuminate\Database\Eloquent\Factories\Factory;

class FeedItemFactory extends Factory
{
    public function definition(): array
    {
        $imageUrl = fake()->optional()->imageUrl();
        $imageMimetype = $imageUrl ? fake()->randomElement(['image/png', 'image/jpeg']) : null;

        return [
            'feed_id' => Feed::factory(),
            'checksum' => fake()->sha256(),
            'url' => fake()->url(),
            'title' => fake()->words(5, true),
            'image_url' => $imageUrl,
            'image_mimetype' => $imageMimetype,
            'description' => fake()->text(),
            'posted_at' => fake()->dateTime(),
            'read_at' => fake()->optional(0.25)->dateTime(),
            'created_at' => fake()->dateTime(),
            'updated_at' => fake()->dateTime(),
        ];
    }

    /**
     * Indicate that the model's read at date is null.
     */
    public function unread(): static
    {
        return $this->state(fn (array $attributes) => [
            'read_at' => null,
        ]);
    }

    /**
     * Indicate that the model's read at date is set.
     */
    public function read(): static
    {
        return $this->state(fn (array $attributes) => [
            'read_at' => fake()->dateTime(),
        ]);
    }
}
