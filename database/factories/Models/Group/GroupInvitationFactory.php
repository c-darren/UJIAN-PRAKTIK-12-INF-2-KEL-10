<?php

namespace Database\Factories\Models\Group;

use App\Models\Auth\User;
use App\Models\Group\GroupList;
use App\Models\Group\GroupInvitation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Group\GroupInvitation>
 */
class GroupInvitationFactory extends Factory
{
    protected $model = GroupInvitation::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'group_list_id' => GroupList::inRandomOrder()->first()->id,
            'user_id' => User::inRandomOrder()->first()->id,
            'status' => $this->faker->randomElement(['Unread', 'Read', 'Rejected', 'Accepted']),
        ];
    }
}
