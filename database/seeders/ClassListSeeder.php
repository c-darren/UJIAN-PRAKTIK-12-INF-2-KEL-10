<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Classroom\ClassList;

class ClassListSeeder extends Seeder
{
    protected static $classLists = [
        // Kelas untuk Master Class 1
        [
            'master_class_id' => 1,
            'class_name' => 'Matematika Dasar 1A',
            'subject_id' => 1,
            'enrollment_status' => 'Open'
        ],
        [
            'master_class_id' => 1,
            'class_name' => 'Matematika Dasar 1B',
            'subject_id' => 2,
            'enrollment_status' => 'Closed'
        ],
        [
            'master_class_id' => 1,
            'class_name' => 'Matematika Lanjut 1C',
            'subject_id' => 3,
            'enrollment_status' => 'Open'
        ],
        [
            'master_class_id' => 1,
            'class_name' => 'Matematika Terapan 1D',
            'subject_id' => 4,
            'enrollment_status' => 'Closed'
        ],
        [
            'master_class_id' => 1,
            'class_name' => 'Matematika Diskrit 1E',
            'subject_id' => 5,
            'enrollment_status' => 'Open'
        ],
        
        // Kelas untuk Master Class 2
        [
            'master_class_id' => 2,
            'class_name' => 'Bahasa Indonesia 2A',
            'subject_id' => 6,
            'enrollment_status' => 'Open'
        ],
        [
            'master_class_id' => 2,
            'class_name' => 'Sastra Indonesia 2B',
            'subject_id' => 7,
            'enrollment_status' => 'Closed'
        ],
        [
            'master_class_id' => 2,
            'class_name' => 'Menulis Kreatif 2C',
            'subject_id' => 8,
            'enrollment_status' => 'Open'
        ],
        [
            'master_class_id' => 2,
            'class_name' => 'Bahasa Jurnalistik 2D',
            'subject_id' => 9,
            'enrollment_status' => 'Closed'
        ],
        [
            'master_class_id' => 2,
            'class_name' => 'Retorika 2E',
            'subject_id' => 10,
            'enrollment_status' => 'Open'
        ],
        
        // Kelas untuk Master Class 3
        [
            'master_class_id' => 3,
            'class_name' => 'Sains Dasar 3A',
            'subject_id' => 11,
            'enrollment_status' => 'Open'
        ],
        [
            'master_class_id' => 3,
            'class_name' => 'Biologi 3B',
            'subject_id' => 12,
            'enrollment_status' => 'Closed'
        ],
        [
            'master_class_id' => 3,
            'class_name' => 'Kimia 3C',
            'subject_id' => 13,
            'enrollment_status' => 'Open'
        ],
        [
            'master_class_id' => 3,
            'class_name' => 'Fisika 3D',
            'subject_id' => 14,
            'enrollment_status' => 'Closed'
        ],
        [
            'master_class_id' => 3,
            'class_name' => 'Astronomi 3E',
            'subject_id' => 15,
            'enrollment_status' => 'Open'
        ],
        
        // Kelas untuk Master Class 4
        [
            'master_class_id' => 4,
            'class_name' => 'Sejarah Nasional 4A',
            'subject_id' => 16,
            'enrollment_status' => 'Open'
        ],
        [
            'master_class_id' => 4,
            'class_name' => 'Sejarah Dunia 4B',
            'subject_id' => 17,
            'enrollment_status' => 'Closed'
        ],
        [
            'master_class_id' => 4,
            'class_name' => 'Geopolitik 4C',
            'subject_id' => 18,
            'enrollment_status' => 'Open'
        ],
        [
            'master_class_id' => 4,
            'class_name' => 'Antropologi 4D',
            'subject_id' => 19,
            'enrollment_status' => 'Closed'
        ],
        [
            'master_class_id' => 5,
            'class_name' => 'Teknologi Informasi 5A',
            'subject_id' => 20,
            'enrollment_status' => 'Open'
        ]
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Class Lists
        foreach (self::$classLists as $classlist) {
            ClassList::create($classlist);
        }

        // Seed Class Teachers
        $classTeachers = [];
        for ($classId = 1; $classId <= 20; $classId++) {
            $classTeachers[] = [
                'class_id' => $classId,
                'teacher_id' => rand(1, 10)
            ];
        }
        DB::table('class_teachers')->insert($classTeachers);

        // Seed Class Students
        $classStudents = [];
        for ($classId = 1; $classId <= 20; $classId++) {
            // Tambahkan 3-5 siswa per kelas
            $studentCount = rand(3, 5);
            $students = collect(range(1, 10))->shuffle()->take($studentCount);
            
            foreach ($students as $student) {
                $classStudents[] = [
                    'class_id' => $classId,
                    'user_id' => $student,
                    'status' => rand(0, 1) ? 'Active' : 'Inactive',
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }
        DB::table('class_students')->insert($classStudents);
    }
}