<?php

namespace App\Models\Group;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\Models\Group\GroupInvitationFactory;

class GroupInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_list_id',
        'user_id',
        'status',
    ];

    protected static function newFactory()
    {
        return GroupInvitationFactory::new();
    }
}
