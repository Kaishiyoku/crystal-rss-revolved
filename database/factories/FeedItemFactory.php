<?php

namespace Database\Factories;

use App\Models\Feed;
use App\Models\FeedItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class FeedItemFactory extends Factory
{
    protected $model = FeedItem::class;

    public function definition(): array
    {
        $imageUrl = $this->faker->optional()->imageUrl();
        $imageMimetype = $imageUrl ? $this->faker->randomElement(['image/png', 'image/jpeg']) : null;

        return [
            'feed_id' => Feed::factory(),
            'checksum' => $this->faker->sha256(),
            'url' => $this->faker->url(),
            'title' => $this->faker->words(5, true),
            'image_url' => $imageUrl,
            'image_mimetype' => $imageMimetype,
            'description' => $this->faker->text(),
            'posted_at' => $this->faker->dateTime(),
            'read_at' => $this->faker->optional(0.25)->dateTime(),
            'created_at' => $this->faker->dateTime(),
            'updated_at' => $this->faker->dateTime(),
        ];
    }
}
