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
<<<<<<< HEAD
=======
            GroupListSeeder::class,
            GroupInvitationSeeder::class,
            GroupMemberSeeder::class,
>>>>>>> refs/remotes/origin/main
            AcademicYearSeeder::class,
            SubjectSeeder::class,
            MasterClassSeeder::class,
            MasterClassStudent::class,
            // ClassListSeeder::class,
        ]);
    }
}
