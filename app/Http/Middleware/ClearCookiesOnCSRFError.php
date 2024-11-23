<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class ClearCookiesOnCSRFError
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Menangani CSRF token mismatch error
        try {
            return $next($request);
        } catch (\Illuminate\Session\TokenMismatchException $e) {
            $this->clearCookies();
            
            Log::warning('CSRF Token Mismatch Detected. Clearing cookies.');

            return response()->json(['error' => 'CSRF token mismatch. Please refresh the page and try again.'], 419);
        }
    }

    private function clearCookies()
    {
        // Menghapus cookie session (atau cookie lain yang perlu dihapus)
        Cookie::queue(Cookie::forget('XSRF-TOKEN'));
        Cookie::queue(Cookie::forget('laravel_session'));
    }
}
