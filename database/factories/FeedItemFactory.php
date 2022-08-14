<?php

namespace Database\Factories;

use App\Models\Feed;
use App\Models\FeedItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FeedItem>
 */
class FeedItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FeedItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'feed_id' => Feed::factory(),
            'url' => $this->faker->url(),
            'title' => $this->faker->sentence(),
            'image_url' => $this->faker->url(),
            'image_mimetype' => $this->faker->mimeType(),
            'description' => $this->faker->text(),
            'posted_at' => $this->faker->dateTimeBetween('-1 week', '-1 day'),
            'checksum' => $this->faker->sha256(),
            'read_at' => $this->faker->optional()->dateTimeBetween('-1 week', '-1 day'),
        ];
    }
}
