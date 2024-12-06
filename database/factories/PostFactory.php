<?php

namespace Database\Factories;

use App\Enums\PostStatusEnum;
use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $authorIds = Author::pluck('id')->toArray();

        return [
            'author_id' => $authorIds[ rand(0, count($authorIds)-1) ],
            'title' => fake()->sentence(),
            'content' => fake()->text(),
            'date' => fake()->dateTime(),
            'status' => $this->faker->randomElement([PostStatusEnum::HIDDEN->value, PostStatusEnum::PUBLISHED->value]),
            'rating' => rand(1, 10),
        ];
    }
}
