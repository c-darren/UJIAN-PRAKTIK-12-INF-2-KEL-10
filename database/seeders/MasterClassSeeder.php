<?php

namespace Database\Seeders;

use App\Models\Classroom\MasterClass;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterClassSeeder extends Seeder
{
    protected static $masterClasses = [
        [
            'id' => 1, 
            'master_class_name'=> 'Test Class 1',
            'master_class_code' => '1',
            'academic_year_id' => '1',
            'status' => 'Active',
        ],
        [
            'id' => 2, 
            'master_class_name'=> 'Test Class 2',
            'master_class_code' => '2',
            'academic_year_id' => '2',
            'status' => 'Active',
        ],
        [
            'id' => 3, 
            'master_class_name'=> 'Test Class 3',
            'master_class_code' => '3',
            'academic_year_id' => '3',
            'status' => 'Active',
        ],
        [
            'id' => 4, 
            'master_class_name'=> 'Test Class 4',
            'master_class_code' => '4',
            'academic_year_id' => 3 ,
            'status' => 'Active',
        ],
        [    
            'id' => 5, 
            'master_class_name'=> 'Test Class 5',
            'master_class_code' => '5',
            'academic_year_id' => 2,
            'status' => 'Active',
        ]
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::$masterClasses as $MasterClass) {
            MasterClass::create($MasterClass);
        }
    }
}
