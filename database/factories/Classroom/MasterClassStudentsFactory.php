<?php

namespace Database\Factories\Classroom;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Classroom\MasterClass>
 */
class MasterClassStudentsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'master_class_id' => random_int(1, 100),
            'user_id' => random_int(10,40),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'status' => $this->faker->randomElement(['Enrolled', 'Exited']),
        ];
    }
}
