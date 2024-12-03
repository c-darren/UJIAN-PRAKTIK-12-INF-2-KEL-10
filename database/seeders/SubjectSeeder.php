<?php

namespace Database\Seeders;

use App\Models\Classroom\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SubjectSeeder extends Seeder
{
    protected $subjects = [
        [
            'subject_name' => 'Matematika'
        ],
        [
            'subject_name' => 'Bahasa Inggris'
        ],
        [
            'subject_name' => 'Bahasa Indonesia'
        ],
        [
            'subject_name' => 'Bahasa Sunda'
        ],
        [
            'subject_name' => 'Seni Budaya'
        ],
        [
            'subject_name' => 'Pendidikan Pancasila dan Kewarganegaraan'
        ],
        [
            'subject_name' => 'Sejarah'
        ],
        [
            'subject_name' => 'Pendidikan Agama Katolik'
        ],
        [
            'subject_name' => 'Matematika Tingkat Lanjut'
        ],
        [
            'subject_name' => 'Bahasa Inggris Tingkat Lanjut'
        ],
        [
            'subject_name' => 'Geografi'
        ],
        [
            'subject_name' => 'Sosiologi'
        ],
        [
            'subject_name' => 'Fisika'
        ],
        [
            'subject_name' => 'Kimia'
        ],
        [
            'subject_name' => 'Informatika'
        ],
        [
            'subject_name' => 'Ekonomi'
        ],
        [
            'subject_name' => 'Biologi'
        ],
        [
            'subject_name' => 'Pendidikan Jasmani, Olahraga, Kesehatan'
        ],
        [
            'subject_name' => 'Bahasa Jawa'
        ],
        [
            'subject_name' => 'Akutansi'
        ],
        [
            'subject_name' => 'Multimedia'
        ],
        [
            'subject_name' => 'Bahasa Jerman'
        ],
        [
            'subject_name' => 'Bahasa Arab'
        ],
        [
            'subject_name' => 'Bahasa Jepang'
        ],
        [
            'subject_name' => 'Bahasa Mandarin'
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->subjects as $subject) {
            Subject::create($subject);
        }
    }
}
