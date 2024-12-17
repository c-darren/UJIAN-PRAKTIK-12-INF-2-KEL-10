<?php

namespace App\Models\Classroom;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'topic_name',
        'created_at',
        'updated_at'
    ];

    public function classList()
    {
        return $this->belongsTo(ClassList::class, 'class_id');
    }
}