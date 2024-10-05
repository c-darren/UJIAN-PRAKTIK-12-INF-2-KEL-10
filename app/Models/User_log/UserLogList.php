<?php

namespace App\Models\User_log;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLogList extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'route_name',
        'description',
    ];

    public function userLogs()
    {
        return $this->hasMany(UserLog::class);
    }
    
}
