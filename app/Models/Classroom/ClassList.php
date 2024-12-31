<?php

namespace App\Models\Classroom;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassList extends Model
{
    use HasFactory;
    protected $fillable = [
        'master_class_id',
        'class_name',
        'subject_id',
        'enrollment_status',
        'created_at',
        'updated_at',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function masterClass()
    {
        return $this->belongsTo(MasterClass::class, 'master_class_id', 'id');
    }

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'class_teachers', 'class_id', 'teacher_id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'class_students', 'class_id', 'user_id')->withTimestamps();
    }
    
    public function presences()
    {
        return $this->hasMany(ClassPresence::class, 'class_id');
    }

    public function attendance()
    {
        return $this->hasMany(ClassAttendance::class, 'class_id');
    }

    public function topics()
    {
        return $this->hasMany(Topic::class, 'class_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'class_students', 'class_id', 'user_id');
    }
}