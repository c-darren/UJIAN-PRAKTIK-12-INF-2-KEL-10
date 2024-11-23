<?php

namespace App\Http\Controllers\Auth;

use App\Models\Auth\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SignupController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.signup');
    }

    /**
     * Handle a registration request.
     */
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name'                  => 'required|string|max:255',
            'username'              => 'required|string|max:255|unique:users,username',
            'email'                 => 'required|email|max:255|unique:users,email',
            'password'              => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
            ],
            'avatar'                => 'nullable|image|mimes:jpeg,png,gif|max:5120', // 5MB
        ], [
            'password.regex' => 'Password must include at least one uppercase letter, one lowercase letter, one number, and one special character.',
        ]);

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        } else {
            $avatarPath = 'avatars/no_image.png';
        }

        $user = User::create([
            'name'              => $validatedData['name'],
            'username'          => $validatedData['username'],
            'avatar'            => $avatarPath,
            'role_id'           => 4,
            'email'             => $validatedData['email'],
            'email_verified_at' => null,
            'password'          => Hash::make($validatedData['password']),
            'remember_token'    => Str::random(60),
        ]);

        if ($user) {
            $user->sendEmailCreateAccount();

            return response()->json([
                'success'       => true,
                'message'       => 'User created successfully. A verification email has been sent to your email address.',
                'redirect_url'  => route('login'),
            ]);
        } else {
            return response()->json([
                'success'   => false,
                'message'   => 'Failed to create user.',
            ], 500);
        }
    }
}
