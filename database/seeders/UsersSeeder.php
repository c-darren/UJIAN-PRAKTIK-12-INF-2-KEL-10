<?php

namespace Database\Seeders;

use App\Models\Users;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Users::create([
            'name' => 'Darren',
            'username' => 'cdarren',
            'avatar' => 'https://i.pravatar.cc/150?img=68',
            'role' => 1,
            'email' => 'darren@darren-project.kencang.id',
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ]);
        Users::factory(10)->create();
    }
}
