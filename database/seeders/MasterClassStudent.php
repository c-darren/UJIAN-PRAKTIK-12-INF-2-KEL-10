<?php

namespace Database\Seeders;

use App\Models\Classroom\MasterClassStudents;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterClassStudent extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MasterClassStudents::factory(10000)->create();
    }
}
