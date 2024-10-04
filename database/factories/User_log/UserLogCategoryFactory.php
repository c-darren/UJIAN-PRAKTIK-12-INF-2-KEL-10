<?php

namespace Database\Factories\User_log;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User_log\UserLogCategory>
 */
class UserLogCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'category' => fake()->words(fake()->numberBetween(1, 3), true),
            // 'description' => fake()->sentence(),
        ];
    }
}
