<?php

namespace App\Models\School;

use App\Models\Classroom\MasterClass;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AcademicYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year',
        'status'
    ];
    
    public function masterClasses()
    {
        return $this->hasMany(MasterClass::class, 'academic_year_id');
    }
}
