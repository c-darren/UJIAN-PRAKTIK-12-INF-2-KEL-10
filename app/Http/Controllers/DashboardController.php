<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(){
        return $this->emailVerifyNotification();
    }

    public function emailVerifyNotification()
    {
        $user = Auth::user();

        $showVerificationAlert = is_null($user->email_verified_at);
        
        return $this->view([
            'showVerificationAlert' => $showVerificationAlert,
            'user' => $user,
        ]);
    }

    public function view(array $data = [])
    {
        return view('dashboard.dashboard', array_merge($data));
    }

}
