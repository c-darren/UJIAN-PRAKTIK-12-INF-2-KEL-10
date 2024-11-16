<?php

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Broadcast;

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });

Broadcast::channel('roles-updates', function ($user) {
    // Periksa roleID dari session
    $roleID = Session::get('roleID');
    return in_array($roleID, [2, 3]);
});

Broadcast::channel('users-updates', function ($user) {
    // Periksa roleID dari session, misalnya hanya mengizinkan role 2
    $roleID = Session::get('roleID');
    return $roleID === 2;
});