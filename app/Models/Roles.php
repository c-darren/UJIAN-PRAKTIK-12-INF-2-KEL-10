<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Roles extends Model
{
    use HasFactory;

    public function users(): HasMany{
        //1 Role memiliki banyak User
        return $this->hasMany(Users::class);
    }
}
