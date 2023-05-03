<?php

namespace Database\Factories;

use App\Models\Feed;
use App\Models\FeedItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class FeedItemFactory extends Factory
{
    protected $model = FeedItem::class;

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
}
