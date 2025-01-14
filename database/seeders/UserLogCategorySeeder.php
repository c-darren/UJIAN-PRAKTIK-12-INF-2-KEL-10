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
                'id' => 1,
                'category' => 'Authentication',
                'description' => 'User Login, Logout',
            ],
            [
                'id' => 2,
                'category' => 'Email Verification & Password Reset',
                'description' => 'Email Verification, Password Reset, etc',
            ],
            [
                'id' => 3,
                'category' => 'Role CRUD',
                'description' => 'Role CRUD by Admin',
            ],
            [
                'id' => 4,
                'category' => 'User Account CRUD',
                'description' => 'User Account CRUD by Admin',
            ],
            [
                'id' => 5,
                'category' => 'Profile',
                'description' => 'Profile Update by User',
            ],
            [
                'id' => 6,
                'category' => 'Academic Year',
                'description' => 'Academic Year CRUD',
            ],
            [
                'id' => 7,
                'category' => 'Subject',
                'description' => 'Subject CRUD',
            ],
            [
                'id' => 8,
                'category' => 'Master Class',
                'description' => 'Master Class CRUD',
            ],
            [
                'id' => 9,
                'category' => 'Master Class Student',
                'description' => 'Master Class Student CRUD',
            ],
            [
                'id' => 10,
                'category' => 'Class List',
                'description' => 'Class List CRUD',
            ],
            [
                'id' => 11,
                'category' => 'Class List Teacher',
                'description' => 'Class List Teacher Create, Read, Delete',
            ],
            [
                'id' => 12,
                'category' => 'Class List Student',
                'description' => 'Class List Student Create, Read, Delete',
            ],
            [
                'id' => 13,
                'category' => 'Classroom Topic',
                'description' => 'Classroom Topic CRUD',
            ],
            [
                'id' => 14,
                'category' => 'Classroom Attendance',
                'description' => 'Classroom Attendance CRUD',
            ],
            [
                'id' => 15,
                'category' => 'Classroom Presence',
                'description' => 'Classroom Presence CRUD',
            ],
            [
                'id' => 16,
                'category' => 'Teacher Resource, Grade, View & Download Content',
                'description' => 'Teacher Resource, Grade, View & Download Content CRUD',
            ],
            [
                'id' => 17,
                'category' => 'Student Master Class',
                'description' => 'Student Master Class CRUD',
            ],
            [
                'id' => 18,
                'category' => 'Student Join Class Lists (Classroom)',
                'description' => 'Student Join Class Lists (Classroom) CRUD',
            ],
            [
                'id' => 19,
                'category' => 'Student Resource, Submission, View & Download Content',
                'description' => 'Student Resource, Submission, View & Download Content CRUD',
            ],
            
        ];

        foreach ($categories as $category) {
            UserLogCategory::firstOrCreate(['category' => $category['category']], $category);
        }
    }
}
