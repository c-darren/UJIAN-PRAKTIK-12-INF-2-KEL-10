<?php

namespace App\Models\Access;

use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Group\GroupList;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SetAccessRoute extends Model
{
    use HasFactory;

    protected $table = 'access_routes';

    protected $fillable = [
        'page_title',
        'page_url',
        'method',
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

    
    /**
     * Many-to-Many relationship with Role model.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'routes_access_role');
    }

    /**
     * Many-to-Many relationship with GroupList model.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(GroupList::class, 'routes_access_group');
    }

}
