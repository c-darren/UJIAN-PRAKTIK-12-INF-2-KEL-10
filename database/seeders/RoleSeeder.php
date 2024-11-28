<?php

namespace Database\Seeders;

use App\Models\Auth\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    protected static $roles = ['admin', 'teacher', 'student', 'parent'];

    public function run()
    {
        foreach (self::$roles as $roleName) {
            Role::create([
                'role' => $roleName,
                'description' => ucfirst($roleName) . ' role',
            ]);
        }
    }
}