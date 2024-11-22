<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use App\Notifications\PasswordResetSuccessNotification;

class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request, $token = null)
    {
        $email = $request->email;

        if (!$token || !$email) {
            return redirect()->route('login')->with('error', 'The reset link is invalid or expired!');
        }
    
        $record = DB::table('password_reset_tokens')
        ->where('email', $request->email)
        ->first();
    
        if (!$record || !Hash::check($request->token, $record->token)) {
            return redirect()->route('login')->with('error', 'The reset link is invalid or expired!');
        }
    
        return view('auth.passwords.reset')->with([
            'token' => $token,
            'email' => $email,
        ]);
    }

    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token'                 => 'required',
            'email'                 => 'required|email|exists:users,email',
            'password'              => 'required|string|min:8|confirmed',
        ], [
            'password.min' => 'Password must be at least 8 characters long.',
            'password.confirmed' => 'Password confirmation does not match.',
            'email.exists' => 'No account found with this email address.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        // Reset password
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password)
                ])->save();

                $user->notify(new PasswordResetSuccessNotification($user->username));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            $this->deleteResetToken($request->email);
            return response()->json([
                'message' => 'Your password has been successfully reset.',
                'redirect' => route('login')
            ]);
        } else {
            return response()->json([
                'message' => 'Invalid token or expired reset link.'
            ], 400);
        }
    }
        protected function deleteResetToken($email)
    {
        DB::table('password_reset_tokens')->where('email', $email)->delete();
    }
}