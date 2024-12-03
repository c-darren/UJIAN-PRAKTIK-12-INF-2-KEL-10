<?php

namespace Database\Seeders;

use App\Models\Auth\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create(
            [
                'name' => 'Test User - Developer',
                'username' => 'user',
                'avatar' => 'avatars/8g5qDqy40lDxs57OkLInlvEMtvaT5UuAS4c1D2hu.png',
                'role_id' => 1,
                'email' => 'darren@darren-project.kencang.id',
                'email_verified_at' => now(),
                'password' => Hash::make('123'),
                'remember_token' => Str::random(10),
            ],
        );
        User::create(
            [
                'name' => 'Student 01',
                'username' => 'student',
                'avatar' => 'avatars/8g5qDqy40lDxs57OkLInlvEMtvaT5UuAS4c1D2hu.png',
                'role_id' => 3,
                'email' => 'student1@darren-project.kencang.id',
                'email_verified_at' => now(),
                'password' => Hash::make('123'),
                'remember_token' => Str::random(10),
            ],
        );
        User::factory(10)->create();
    }
}
