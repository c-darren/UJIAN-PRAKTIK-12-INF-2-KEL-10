<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
// use App\Models\Auth\User;

class Role extends Model
{
    use HasFactory;

    protected $dates = ['deleted_at'];


    public function users(): HasMany{
        //1 Role memiliki banyak User
        return $this->hasMany(User::class);
    }
}
