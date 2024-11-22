<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CSRFController extends Controller
{
    public function refresh(Request $request)
    {
        // Hapus cookie CSRF lama jika ada
        $cookieName = 'XSRF-TOKEN';
        setcookie($cookieName, '', time() - 3600, '/'); // Menghapus cookie dengan masa kadaluarsa di masa lalu

        // Regenerasi CSRF token dan simpan di sesi
        $newToken = csrf_token();

        // Kembalikan token baru sebagai respons
        return response()->json([
            'csrf_token' => $newToken
        ])->cookie('XSRF-TOKEN', $newToken, 60, '/', null, false, false);
    }
}
