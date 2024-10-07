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
                'route_name' => 'login',
                'method' => 'POST',
                'description' => 'User Login',
            ],
            [
                'category_id' => '1',
                'route_name' => 'login',
                'method' => 'GET',
                'description' => 'User Login',
            ],
            [
                'category_id' => '1',
                'route_name' => 'logout',
                'method' => 'POST',
                'description' => 'User Logout',
            ],
            [
                'category_id' => '1',
                'route_name' => '/',
                'method' => 'GET',
                'description' => 'User Submit the Task',
            ],
            [
                'category_id' => '1',
                'route_name' => 'dashboard',
                'method' => 'GET',
                'description' => 'User View Dashboard',
            ],
        ];

        foreach ($lists as $list) {
            UserLogList::create($list);
        }
    }
}
