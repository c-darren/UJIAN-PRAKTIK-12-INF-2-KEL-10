<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            UserLogCategorySeeder::class,
            UserLogListSeeder::class,
            GroupListSeeder::class,
            GroupInvitationSeeder::class,
            GroupMemberSeeder::class,
            AcademicYearSeeder::class,
            SubjectSeeder::class,
            MasterClassSeeder::class
        ]);
    }
}
