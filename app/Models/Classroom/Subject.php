<?php

namespace App\Models\Classroom;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $table = 'subjects';
    protected $fillable = ['id', 'subject_name'];

    public $timestamps = false;

    public function classLists()
    {
        return $this->hasMany(ClassList::class, 'subject_id');
    }
}
