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

        // Cek apakah input merupakan email atau username
        $loginType = filter_var($validated['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Temukan pengguna berdasarkan input (username/email)
        $user = User::where($loginType, $validated['login'])->first();

        // Jika pengguna ditemukan dan password cocok
        if ($user && Hash::check($validated['password'], $user->password)) {
            // Pastikan email sudah terverifikasi
            if ($user->email_verified_at === null) {
                session()->flash('error', 'Email Anda belum terverifikasi.');
                return back(); // Kembalikan ke form login dengan pesan error
            }

            // Login pengguna
            Auth::login($user);

            // Set session berhasil login
            session()->flash('success', 'Login berhasil!');

            // Redirect setelah login sukses
            return redirect()->intended('/dashboard');
        }

        // Jika gagal login, kembalikan pesan error
        session()->flash('error', 'Kredensial tidak cocok dengan data kami.');
        return back();
    }
}
