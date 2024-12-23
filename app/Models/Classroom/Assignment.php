<?php

namespace App\Models\Classroom;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Assignment extends Model
{
    use HasFactory;
    protected $table = 'assignments';
    protected $fillable = [
        'class_id',
        'topic_id',
        'assignment_name',
        'description',
        'start_date',
        'end_date',
        'accept_late_submissions',
        'attachment',
        'author_id',
        'editor_id',
        'updated_at',
    ];

    protected $casts = [
        'accept_late_submissions' => 'boolean',
        // 'attachment_url' => 'array',
    ];
    
    public function topic()
    {
        return $this->belongsTo(Topic::class, 'topic_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'editor_id');
    }

    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    public function classList()
    {
        return $this->belongsTo(ClassList::class, 'class_id');
    }
}
