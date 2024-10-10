<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\Auth\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login', ['title' => 'Login']);
    }

    public function login(Request $request)
    {
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
                        'message' => 'Password salah'
                    ]);
                }
                
                // Jika bukan AJAX, kembali ke halaman sebelumnya
                session()->flash('error', 'Password salah');
                return back();}
    
            if ($user->email_verified_at === null) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Email belum terverifikasi'
                    ]);
                }
                
                session()->flash('error', 'Email belum terverifikasi');
                return back();
            }
    
            Auth::login($user);
    
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'redirect_url' => url('/dashboard')
                ]);
            }
    
            session()->flash('success', 'Login berhasil!');
            return back();
            // return redirect()->intended('/dashboard');
        }
    
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Akun pengguna tidak ditemukan'
            ]);
        }
    
        session()->flash('error', 'Akun pengguna tidak ditemukan');
        return back();
    }
}
