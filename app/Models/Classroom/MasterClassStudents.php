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
    protected $with = ['user', 'masterClass'];

    public function masterClass()
    {
        return $this->belongsTo(MasterClass::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activeMasterClass()
    {
        return $this->belongsTo(MasterClass::class, 'master_class_id')
            ->where('status', 'Active');
    }

    public function activeAcademicYear()
    {
        return $this->belongsTo(MasterClass::class, 'master_class_id')
            ->whereHas('academicYear', function($query) {
                $query->where('status', 'Active');
            });
    }
}
