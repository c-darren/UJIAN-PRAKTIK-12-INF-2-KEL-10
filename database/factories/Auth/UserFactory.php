<?php

namespace Database\Factories\Auth;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Auth\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * The default state should be modified if needed.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // The user name.
            'name' => fake()->name(),

            // The user's username.
            'username' => fake()->userName(),

            // The user's avatar.
            'avatar' => 'avatars/default.png',

            // The user's role.
            // The role should be one of the following: 1,2,3.
            'role_id' => fake()->randomElement([1,2,3,4]),

            // The user's email address.
            'email' => fake()->unique()->safeEmail(),

            // The user's email verified at timestamp.
            // The user's email is verified.
            'email_verified_at' => now(),

            // The user's password.
            // The password will be hashed.
            // The default password is "password".
            'password' => static::$password ??= Hash::make('password'),

            // The user's remember token.
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
