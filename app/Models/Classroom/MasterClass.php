<?php

namespace App\Models\Classroom;

use App\Models\School\AcademicYear;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterClass extends Model
{
    use HasFactory;

    protected $table = 'master_classes';

    protected $fillable = [
        'master_class_name',
        'master_class_code',
        'academic_year_id',
        'status',
    ];

    public function academic_year_relation()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id', 'id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class)
            ->select(['id', 'academic_year', 'status']);
    }

    public function students()
    {
        return $this->hasMany(MasterClassStudents::class);
    }

    public function classLists()
    {
        return $this->hasMany(ClassList::class, 'master_class_id');
    }

    public function activeStudents()
    {
        return $this->hasMany(MasterClassStudents::class)
            ->where('status', 'Enrolled');
    }

    public function exitedStudents()
    {
        return $this->hasMany(MasterClassStudents::class)
            ->where('status', 'Exited');
    }
}
