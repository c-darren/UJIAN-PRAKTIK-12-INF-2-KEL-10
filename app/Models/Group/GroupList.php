<?php

namespace App\Models\Group;

use Illuminate\Database\Eloquent\Model;
use Database\Factories\Models\Group\GroupListFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GroupList extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_name',
        'author_id',
        'status',
        'code',
        'valid_until',
        'description'
    ];

    protected $dates =['deleted_at'];

    protected static function newFactory()
    {
        return GroupListFactory::new();
    }
}
