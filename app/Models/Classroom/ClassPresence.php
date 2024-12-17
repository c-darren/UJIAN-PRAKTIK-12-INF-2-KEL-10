<?php

namespace App\Models\Classroom;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassPresence extends Model
{
    use HasFactory;

    protected $table = 'class_presences';

    protected $fillable = [
        'attendance_id',
        'user_id',
        'status',
    ];

    // Nonaktifkan auto-incrementing karena tidak ada kolom 'id'
    public $incrementing = false;

    // Tidak menetapkan primaryKey secara spesifik
    protected $primaryKey = null;

    // Jika Anda menggunakan timestamps
    public $timestamps = true;

    /**
     * Relasi ke ClassAttendance
     */
    public function attendance()
    {
        return $this->belongsTo(ClassAttendance::class, 'attendance_id');
    }

    /**
     * Relasi ke Student (User)
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}