<?php

namespace Database\Factories\Classroom;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Classroom\MasterClass>
 */
class MasterClassFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'master_class_name' => $this->faker->word(),
            'master_class_code' => $this->generateRandomCode(), // Kode kelas acak (2-6 karakter)
            'academic_year_id' => random_int(1, 3),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'status' => $this->faker->randomElement(['Active', 'Archived']),
        ];
    }

    private function generateRandomCode(): string
    {
        $length = rand(10, 25);
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'; // Karakter yang bisa digunakan
        $code = '';
        
        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $code;
    }
}
