<?php

namespace App\Http\Controllers\Profile;

use Carbon\Carbon;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Notifications\VerifyNewEmailNotification;
use App\Notifications\PasswordChangedNotification;
use App\Notifications\CancelEmailChangeNotification;

class ProfileController extends Controller
{
    public function index()
    {
        return $this->view('menu');
    }

    public function show()
    {
        return $this->view('menu');
    }

    public function edit()
    {
        // $user = User::findOrFail(session('userID'));
        $user = Auth::user();
        return $this->view('edit_profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = User::findOrFail(Auth::user()->id);

        // Validasi data input
        $validatedData = $request->validate([
            'name'      => 'required|string|max:255',
            'username'  => 'required|string|max:255|unique:users,username,' . $user->id,
            'email'     => 'required|email|max:255|unique:users,email,' . $user->id,
            'avatar'    => 'nullable|image|mimes:jpeg,png,gif|max:5120', // Batas ukuran 5 MB dan tipe file yang diterima
        ]);

        $lastUpdateTime = Session::get('last_profile_update_time', null);
        if ($lastUpdateTime) {
            $lastUpdateTime = Carbon::parse($lastUpdateTime);

            $secondsLeft = now()->diffInSeconds($lastUpdateTime->addSeconds(60), false);
            $roundedSecondsLeft = round($secondsLeft);

            if ($secondsLeft > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Please wait {$roundedSecondsLeft} seconds before trying to update your profile again.",
                    'secondsLeft' => $roundedSecondsLeft
                ], 429);
            }
        }

        $avatarPath = $user->avatar;
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        // Cek apakah ada perubahan pada nama, username, atau email
        $emailChanged = $user->email !== $validatedData['email'];
        $newEmail = $validatedData['email'];
        $nameChanged = $user->name !== $validatedData['name'];
        $usernameChanged = $user->username !== $validatedData['username'];

        // Jika tidak ada perubahan pada data yang relevan, batalkan update
        if (!$nameChanged && !$usernameChanged && !$emailChanged && !$request->hasFile('avatar')) {
            return response()->json([
                'success' => false,
                'message' => 'No changes made to the profile.'
            ]);
        }

        // Perbarui data pengguna
        $user->name = $validatedData['name'];
        $user->username = $validatedData['username'];
        $user->avatar = $avatarPath;

        
        if ($emailChanged) {
            $user->email = $validatedData['email'];
            $user->email_verified_at = null;
            $this->sendVerifyNewEmailNotification($user, $newEmail);
        }
        $user->save();

        Session::put('last_profile_update_time', now());

        return response()->json(['success' => true, 'message' => 'Profile updated successfully.']);
    }
    
    public function editPassword()
    {
        return $this->view('edit_password');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        // Validasi data input
        $request->validate([
            'current_password'      => 'required',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/',
            ],
        ]);

        // Cek apakah current_password cocok dengan password pengguna
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect.',
            ], 422);
        }

        // Cek apakah password baru sama dengan password lama
        if (Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'New password cannot be the same as the current password.',
            ], 422);
        }

        // Update password pengguna
        $user->password = Hash::make($request->password);
        $user->save();

        $this->sendPasswordChangeNotification($user);
        
        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully.',
        ]);
    }

    private function sendCancelChangeNotification($user, $oldEmail, $newEmail)
    {
        $cancelUrl = URL::temporarySignedRoute(
            'email.cancel-change',
            now()->addMinutes(5),
            ['user' => $user->id, 'email' => $newEmail]
        );

        $user->notify(new CancelEmailChangeNotification($user->username, $newEmail, $cancelUrl));
    }

    private function sendVerifyNewEmailNotification($user, $newEmail)
    {
        $user = User::findOrFail(Auth::user()->id);

        // Validasi data input
        $validatedData = $request->validate([
            'name'      => 'required|string|max:255',
            'username'  => 'required|string|max:255|unique:users,username,' . $user->id,
            'email'     => 'required|email|max:255|unique:users,email,' . $user->id,
            'avatar'    => 'nullable|image|mimes:jpeg,png,gif|max:5120', // Batas ukuran 5 MB dan tipe file yang diterima
        ]);

        $lastUpdateTime = Session::get('last_profile_update_time', null);
        if ($lastUpdateTime) {
            $lastUpdateTime = Carbon::parse($lastUpdateTime);

            $secondsLeft = now()->diffInSeconds($lastUpdateTime->addSeconds(60), false);
            $roundedSecondsLeft = round($secondsLeft);

            if ($secondsLeft > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Please wait {$roundedSecondsLeft} seconds before trying to update your profile again.",
                    'secondsLeft' => $roundedSecondsLeft
                ], 429);
            }
        }

        $avatarPath = $user->avatar;
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        // Cek apakah ada perubahan pada nama, username, atau email
        $emailChanged = $user->email !== $validatedData['email'];
        $newEmail = $validatedData['email'];
        $nameChanged = $user->name !== $validatedData['name'];
        $usernameChanged = $user->username !== $validatedData['username'];

        // Jika tidak ada perubahan pada data yang relevan, batalkan update
        if (!$nameChanged && !$usernameChanged && !$emailChanged && !$request->hasFile('avatar')) {
            return response()->json([
                'success' => false,
                'message' => 'No changes made to the profile.'
            ]);
        }

        // Perbarui data pengguna
        $user->name = $validatedData['name'];
        $user->username = $validatedData['username'];
        $user->avatar = $avatarPath;

        
        if ($emailChanged) {
            $user->email = $validatedData['email'];
            $user->email_verified_at = null;
            $user->save();
            $this->sendVerifyNewEmailNotification($user, $newEmail);
        }
        $user->save();

        Session::put('last_profile_update_time', now());

        return response()->json(['success' => true, 'message' => 'Profile updated successfully.']);
    }

    public function cancelChangeEmail(Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(403, 'Invalid or expired verification link.');
        }

        $user = User::findOrFail($request->input('user'));

        if ($user->email !== $request->input('email')) {
            abort(403, 'This action is no longer valid.');
        }

        // Kembalikan email lama dan tandai sebagai terverifikasi
        $user->email = $user->getOriginal('email');
        $user->email_verified_at = now();
        $user->save();

        return redirect()->route('profile.show')->with('success', 'Perubahan email telah dibatalkan.');
    }

    public function verifyNewEmail(Request $request, $user, $email)
    {
        // Validasi tanda tangan URL
        if (!$request->hasValidSignature()) {
            abort(403, 'Invalid or expired URL.');
        }
    
        // Validasi keberadaan user
        $userModel = User::findOrFail($user);
    
        // Cocokkan email
        if ($userModel->email !== $email) {
            abort(403, 'Invalid email address.');
        }
    
        // Update email
        $userModel->email = $email;
        $userModel->save();
    
        return redirect()->route('dashboard')->with('success', 'Email successfully verified.');
    }    

    protected function sendPasswordChangeNotification($user)
    {
        // Extract necessary attributes
        $userData = [
            'id'       => $user->id,
            'name'     => $user->name,
            'username' => $user->username,
            'email'    => $user->email,
        ];
    
        // Pass only the necessary data to the notification
        $user->notify(new PasswordChangedNotification($userData));
    }

    private function view($view, $data = [])
    {
        return view("dashboard.profile.$view", $data);
    }
}
