<?php

namespace Database\Factories\Auth;

use App\Models\Auth\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Auth\Role>
 */
class RoleFactory extends Factory
{
    protected static $roles = ['staff', 'developer', 'admin', 'guest', 'student', 'teacher', 'parent', 'parent_guardian'];
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        if(empty(static::$roles)){
            $role = fake()->words(fake()->numberBetween(1, 8), true);
        } else {
            $roleIndex = array_rand(static::$roles);
            $role = static::$roles[$roleIndex];

            unset(static::$roles[$roleIndex]);

            static::$roles = array_values(static::$roles);
        }

        return [
            'role' => $role,
            'description' => fake()->sentence(),
        ];
    }
}
