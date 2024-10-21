<?php

namespace App\Models\Access;

use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Group\GroupList;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SetAccessRoutePrefix extends Model
{
    protected $table = 'access_routes_prefixes';
    protected $dates = ['start_date', 'valid_until'];
    use HasFactory;


    protected $fillable = [
        'name',
        'prefix',
        'creator_id',
        'editor_id',
        'ip_address',
        'type_ip_address',
        'status',
        'start_date',
        'valid_until',
        'type_group_list',
        'description',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'editor_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'route_prefixes_access_role', 'set_access_route_prefix_id', 'role_id');
    }

    public function groups()
    {
        return $this->belongsToMany(GroupList::class, 'route_prefixes_access_group', 'set_access_route_prefix_id', 'group_list_id');
    }

}
