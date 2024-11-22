<?php

namespace App\Http\Controllers\Auth;

use App\Models\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use App\Notifications\PasswordResetLinkNotification;

class ForgotPasswordController extends Controller
{
    // Menampilkan form permintaan reset password
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email']
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        $token = Password::createToken($user);
    
        $resetUrl = URL::temporarySignedRoute(
            'password.reset', 
            now()->addMinutes(15), 
            [
                'token' => $token,
                'email' => $user->email
            ]
        );
    
        $user->notify(new PasswordResetLinkNotification($user->username, $resetUrl));
    
        return response()->json([
            'message' => 'Password reset link has been sent to your email!',
            'redirect' => route('login')
        ]);
    }

    public function resetPasswordSuccess()
    {
        return redirect()->route('login')->with('success', 'Password reset link has been sent to your email!');
    }
}
