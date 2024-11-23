<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\Auth\Role;
use App\Models\Auth\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if(Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login', ['title' => 'Login']);
    }

    public function login(Request $request)
    {
        if(Auth::check()) {
            return redirect()->route('dashboard');
        }
        // Validasi input
        $validated = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginType = filter_var($validated['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $user = User::where($loginType, $validated['login'])->first();

        if ($user) {
            if (!Hash::check($validated['password'], $user->password)) {
                // Jika request AJAX, kembalikan JSON response
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Wrong Password'
                    ]);
                }
                
                // Jika bukan AJAX, kembali ke halaman sebelumnya
                session()->flash('error', 'Wrong Password');
                return back();}
    
            Auth::login($user);
            $role_name = Role::where('id', $user->role_id)->value('role');

            session()->put([
                'userID' => $user->id,
                'roleID' => $user->role_id,
                'role' => $role_name,
                'username' => $user->username,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar
            ]);
    
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'redirect_url' => url('/dashboard')
                ]);
            }
            session()->flash('success', json_encode(session()->all()));    
            // session()->flash('success', 'Login berhasil!');
            return back();
            // return redirect()->intended('/dashboard');
        }
    
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'User account not found'
            ]);
        }
    
        session()->flash('error', 'User account not found');
        return back();
    }
}
