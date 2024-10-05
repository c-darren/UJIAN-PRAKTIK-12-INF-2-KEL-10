<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User_log\UserLogCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserLogCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'category-id' => 'Authentication',
                'description' => 'User Login, Logout, etc',
            ],
        ];

        foreach ($categories as $category) {
            UserLogCategory::firstOrCreate(['category' => $category['category']], $category);
        }
    }
}
