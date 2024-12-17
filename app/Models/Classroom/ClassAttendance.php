<?php

namespace App\Models\Classroom;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassAttendance extends Model
{
    use HasFactory;

    protected $table = 'class_attendances';

    protected $fillable = [
        'class_id',
        'topic_id',
        'attendance_date',
        'description',
        'updated_at',
    ];

    public function classList()
    {
        return $this->belongsTo(ClassList::class, 'class_id');
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class, 'topic_id');
    }

    public function presences()
    {
        return $this->hasMany(ClassPresence::class, 'attendance_id');
    }
}
