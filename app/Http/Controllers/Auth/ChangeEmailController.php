<?php

namespace App\Http\Controllers\Auth;

use App\Models\Auth\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ChangeEmailController extends Controller
{
    /**
     * Verifikasi email baru.
     */
    public function verify(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (!hash_equals((string) $hash, sha1($request->input('email')))) {
            abort(403, 'Invalid email verification link.');
        }

        // Update email user
        $user->email = $request->input('email');
        $user->email_verified_at = now();
        $user->save();

        return redirect()->route('dashboard')->with('success', 'Your email address has been updated.');
    }
}
