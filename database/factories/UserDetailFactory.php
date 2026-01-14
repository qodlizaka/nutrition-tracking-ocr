<?php

namespace Database\Factories;

use App\Enum\PhysicalActivityLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserDetail>
 */
class UserDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'akg_id' => rand(1, 19),
            'weight' => fake()->randomFloat(2, 20, 100),
            'height' => fake()->randomFloat(2, 100, 200),
            'activity_level' => fake()->randomElement(PhysicalActivityLevel::cases()),
            'created_at' => now()->subDays(rand(7, 365)),
        ];
    }
}
