<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "book_id" => null,
            "review" => fake()->paragraph(),
            "rating" => fake()->randomFloat(2, 0, 5),
            "created_at" => fake()->dateTimeBetween("-2 years"),
            "updated_at" => function (array $attributes) {
                return fake()->dateTimeBetween($attributes["created_at"], "now");
            },
        ];
    }
    public function good()
    {
        return $this->state(function ($attributes) {
            return [
                "rating" => fake()->randomFloat(2, 4, 5)
            ];
        });
    }

    public function avg()
    {
        return $this->state(function ($attributes) {
            return [
                "rating" => fake()->randomFloat(2, 3, 4)
            ];
        });
    }
    public function bad()
    {
        return $this->state(function ($attributes) {
            return [
                "rating" => fake()->randomFloat(2, 0, 2)
            ];
        });
    }
}
