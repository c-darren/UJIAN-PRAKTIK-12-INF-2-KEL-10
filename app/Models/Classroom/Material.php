<?php

namespace App\Models\Classroom;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'topic_id',
        'material_name',
        'description',
        'start_date',
        'attachment',
        'author_id',
        'editor_id',
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

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'editor_id');
    }
}