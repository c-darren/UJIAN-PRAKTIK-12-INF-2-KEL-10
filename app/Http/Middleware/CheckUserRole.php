<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$allowedRoleID): Response
    {
        $sessionRoleId = Session::get('roleID');
    
        // Jika roleID tidak ada
        if (empty($sessionRoleId)) {
            if ($request->segment(1) === 'login') {
                return $next($request);
            } else {
                return redirect()->route('login');
            }
        }
        
        // Jika pengguna sudah login dan mencoba akses login
        if ($request->segment(2) === 'login') {
            return redirect()->route('dashboard'); // Redirect ke dashboard jika sudah login
        }
        
        // Memeriksa apakah roleID valid
        if (!in_array($sessionRoleId, $allowedRoleID)) {
            if($request->segment(1) === 'dashboard') {
                return $next($request);
            }else{
                return redirect()->route('dashboard');
            }
        }
    
        return $next($request);
    }
}
