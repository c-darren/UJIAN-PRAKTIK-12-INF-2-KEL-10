<?php

namespace Database\Seeders;

use App\Models\School\AcademicYear;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AcademicYearSeeder extends Seeder
{
    protected static $academicYears = [
        ['id' => 1, 'academic_year' => '2022-2023'],
        ['id' => 2, 'academic_year' => '2023-2024'],
        ['id' => 3, 'academic_year' => '2024-2025'],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::$academicYears as $academicYear) {
            AcademicYear::create($academicYear);
        }
    }
}
