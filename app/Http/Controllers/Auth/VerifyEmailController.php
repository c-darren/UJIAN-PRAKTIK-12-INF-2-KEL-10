<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Session;

class VerifyEmailController extends Controller
{
    public function verify(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        // Verifikasi hash
        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return redirect('/login')->with('error', 'Invalid verification link.');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect('/login')->with('message', 'Email already verified.');
        }

        $user->markEmailAsVerified();

        event(new Verified($user));

        return redirect()->route('dashboard')->with('verified', true);
    }

    public function resend(Request $request)
    {
        $userID = session('userID');
        $user = User::findOrFail($userID);
    
        // Pastikan pengguna belum memverifikasi email
        if ($user->email_verified_at) {
            return back()->withErrors([
                'message' => 'Your email is already verified.',
            ]);
        }
    
        // Cek apakah user dalam periode cooldown (60 detik)
        $lastSentTime = Session::get('last_verification_email_sent', null);

        if ($lastSentTime) {
            $lastSentTime = Carbon::parse($lastSentTime); // Ubah menjadi Carbon instance
            
            // Hitung waktu tersisa
            $secondsLeft = now()->diffInSeconds($lastSentTime->addSeconds(60), false);
            $roundedSecondsLeft = round($secondsLeft);
            // Jika masih dalam periode cooldown
            if ($secondsLeft > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Please wait {$roundedSecondsLeft} seconds before sending another verification email.",
                    'secondsLeft' => round($roundedSecondsLeft)
                ], 429);
            }
        }        

        // Kirim ulang email verifikasi
        $user->sendEmailVerificationNotificationCustom();

        // Simpan waktu terakhir pengiriman di sesi
        Session::put('last_verification_email_sent', now());

        return response()->json([
            'success' => true,
            'message' => 'Verification email resent successfully.',
            'secondsLeft' => 60
        ]);
    }
    
}