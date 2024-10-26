<?php

namespace Database\Factories\Models\Group;
use App\Models\Group\GroupList;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Group\GroupList>
 */
class GroupListFactory extends Factory
{
    protected $model = GroupList::class;
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'group_name' => 'Test Group' . $this->faker->randomNumber(3),
            'author_id' => 1,
            'status' => $this->faker->randomElement(['Open', 'Close']),
            'code' => $this->generateRandomCode(),
            'valid_until' => now()->addDays(30),
            'description' => $this->faker->sentence(2),
        ];
    }
    private function generateRandomCode()
    {
        return strtoupper(substr(md5(rand()), 0, 8)) .
            $this->faker->randomNumber(3) .
            $this->faker->randomLetter() .
            $this->faker->randomElement(['!', '@', '#', '$', '%', '^', '&', '*']);
    }
}
