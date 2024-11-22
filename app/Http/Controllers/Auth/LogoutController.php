<?php

namespace App\Http\Controllers\Auth;

use App\Models\Auth\Logout;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        Auth::logout();
    
        $request->session()->invalidate();
    
        $request->session()->regenerateToken();
    
        // Delete all cookies by setting their expiration time to the past
        foreach ($request->cookies->all() as $cookieName => $cookieValue) {
            Cookie::queue(Cookie::forget($cookieName));
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Logged out successfully.']);
        }
    
        return redirect('/login')->with('status', 'You have been logged out successfully.');
    }
}
