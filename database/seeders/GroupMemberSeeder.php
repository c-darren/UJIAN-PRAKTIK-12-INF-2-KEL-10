<?php

namespace Database\Seeders;

use App\Models\Auth\User;
use App\Models\Group\GroupList;
use Illuminate\Database\Seeder;
use App\Models\Group\GroupMember;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GroupMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groupLists = GroupList::all();

        foreach ($groupLists as $groupList) {
            for ($i = 0; $i < 10; $i++) {
                $user = User::inRandomOrder()->first();
                
                GroupMember::create([
                    'group_list_id' => $groupList->id,
                    'user_id' => $user->id,
                    'join_type' => 'Invitation',
                    'join_date' => now(),
                ]);
            }
        }

        while (GroupMember::count() < 35) {
            $user = User::inRandomOrder()->first();
            $groupList = $groupLists->random();
            
            GroupMember::create([
                'group_list_id' => $groupList->id,
                'user_id' => $user->id,
                'join_type' => 'Code',
                'join_date' => now(),
            ]);
        }
    }
}
