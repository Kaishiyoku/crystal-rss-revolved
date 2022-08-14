<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Feed;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Feed>
 */
class FeedFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Feed::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'feed_url' => $this->faker->url(),
            'site_url' => $this->faker->url(),
            'favicon_url' => $this->faker->optional(0.2)->url(),
            'name' => $this->faker->name(),
            'language' => $this->faker->languageCode(),
            'last_checked_at' => $this->faker->dateTimeBetween('-1 week'),
        ];
    }
}
