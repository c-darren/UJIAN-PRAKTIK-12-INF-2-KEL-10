<?php

namespace App\Models\Access;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetPassword extends Model
{
    use HasFactory;
    //Password akan dapat dibedakan setiap role, dapat juga dipilih tidak memerlukan password, dapat juga dikunci bagi role tertentu
}
