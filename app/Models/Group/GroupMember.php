<?php

namespace App\Models\Group;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_list_id',
        'user_id',
        'group_role',
        'join_type',
        'join_date',
        'leave_date',
    ];

    public $timestamps = false;
}
