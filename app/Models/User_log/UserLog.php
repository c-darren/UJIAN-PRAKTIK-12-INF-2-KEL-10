<?php

namespace App\Models\User_log;

use App\Models\Auth\Role;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserLog extends Model
{
    // protected $table = 'user_logs';
    use HasFactory;

    protected $fillable = [
        'user_id',
        'role_id',
        'ip_address',
        'category_id',
        'list_id',
        'description'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(UserLogCategory::class, 'category_id');
    }

    public function list(): BelongsTo
    {
        return $this->belongsTo(UserLogList::class, 'list_id');
    }
}
