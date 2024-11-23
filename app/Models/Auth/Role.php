<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use App\Models\Auth\User;

class Role extends Model
{
    use HasFactory, Notifiable, SoftDeletes;


    protected $dates = ['deleted_at'];

    protected $fillable = [
        'role',
        'description'
    ];

    public function users(): HasMany{
        //1 Role memiliki banyak User
        return $this->hasMany(User::class);
    }
}
