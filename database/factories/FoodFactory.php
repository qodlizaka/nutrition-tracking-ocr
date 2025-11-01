<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Food>
 */
class FoodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(rand(1, 3), true),
            'image' => fake()->imageUrl(),
            'total_servings' => (int) rand(20, 200) / 10 * 10,
            'unit' => fake()->randomElement(['gr', 'ml']),
        ];
    }
}
