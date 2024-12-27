<?php

namespace App\Models\Classroom;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssignmentSubmission extends Model
{
    use HasFactory;

    protected $table = 'assignment_submissions';
    
    protected $fillable = [
        'assignment_id',
        'user_id', 
        'attachment',
        'attachment_file_name',
        'score',
        'feedback',
        'assessed_by',
        'return_status',
        'scheduled_return_at',
        'returned_at'
    ];

    protected $casts = [
        'score' => 'float',
        'scheduled_return_at' => 'datetime',
        'returned_at' => 'datetime'
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assessor()
    {
        return $this->belongsTo(User::class, 'assessed_by');
    }

    public function feedbackUsers()
    {
        return $this->belongsToMany(User::class, 'users', 'id', 'id', 'user_id');
    }
}