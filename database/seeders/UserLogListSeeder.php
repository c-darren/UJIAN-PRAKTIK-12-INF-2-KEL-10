<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User_log\UserLogList;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserLogListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lists = [
            [
                'category_id' => '1',
                'route_name' => '/login',
                'description' => 'User Login',
            ],
            [
                'category_id' => '1',
                'route_name' => '/logout',
                'description' => 'User Logout',
            ],
            [
                'category_id' => '1',
                'route_name' => '/',
                'description' => 'User Submit the Task',
            ],
        ];

        foreach ($lists as $list) {
            UserLogList::create($list);
        }
    }
}
