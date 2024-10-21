<?php

namespace Database\Seeders;

use App\Models\Group\GroupList;
use Illuminate\Database\Seeder;
use App\Models\Group\GroupInvitation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GroupInvitationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $groupLists = GroupList::all();

        foreach ($groupLists as $groupList) {
            for ($i = 0; $i < 10; $i++) {
                GroupInvitation::factory()->create([
                    'group_list_id' => $groupList->id,
                ]);
            }
        }

        while (GroupInvitation::count() < 55) {
            GroupInvitation::factory()->create([
                'group_list_id' => $groupLists->random()->id,
            ]);
        }
    }
}
