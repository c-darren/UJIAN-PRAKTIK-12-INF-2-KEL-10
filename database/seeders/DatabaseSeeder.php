<?php

namespace Database\Seeders;

use App\Models\Users;
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
<<<<<<< Updated upstream
            UsersSeeder::class,
=======
            RoleSeeder::class,
            UserSeeder::class,
            UserLogCategorySeeder::class,
>>>>>>> Stashed changes
        ]);
    }
}
