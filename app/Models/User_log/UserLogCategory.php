<?php

namespace App\Models\User_log;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLogCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'description',
    ];

    public function userLogs()
    {
        return $this->hasMany(UserLog::class);
    }
}
