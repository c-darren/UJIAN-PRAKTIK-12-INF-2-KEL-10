<?php

namespace App\Models\Classroom;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterClassStudents extends Model
{
    use HasFactory;

    protected $table = 'master_class_students';
    protected $fillable = ['master_class_id', 'user_id'];

    public function masterClass()
    {
        return $this->belongsTo(MasterClass::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
