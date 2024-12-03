<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Classroom\MasterClassStudentController;

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
        if (auth()->user()->role_id == 3) {
            // $masterClassStudentController = new MasterClassStudentController();
            // return $masterClassStudentController->showEnrolled();

            return redirect()->route('master-class.enrolled-class');
        }
        return view('dashboard.dashboard.dashboard', array_merge($data));
    }

}
