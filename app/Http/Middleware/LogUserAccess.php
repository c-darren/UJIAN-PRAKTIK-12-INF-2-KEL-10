<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User_log\UserLog;
use App\Models\User_log\UserLogList;
use App\Models\User_log\UnknownUserLog;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LogUserAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ipAddress = $request->ip();
        $routeName = $request->route()->getName();
        $method = $request->method();
        
        $userLogList = UserLogList::where('route_name', $routeName)
            ->where('method', $method)
            ->first();

        // // 1. Tampilkan semua data dari request
        // dump($request->all());
            
        // // 2. Dapatkan dan tampilkan IP Address
        // $ipAddress = $request->ip();
        // dump($ipAddress);

        // // 3. Dapatkan dan tampilkan nama rute
        // $routeName = $request->route()->getName();
        // dump($routeName);

        // // 4. Dapatkan dan tampilkan metode permintaan
        // $method = $request->method();
        // dump($method);

        // dump($userLogList);


        if ($userLogList) {
            UserLog::create([
                'user_id' => Auth::id(),
                'role_id' => Auth::user()->role_id,
                'ip_address' => $ipAddress,
                'category_id' => $userLogList->category_id,
                'list_id' => $userLogList->id,
                'description' => $userLogList->description,
            ]);
        } else {
            UnknownUserLog::create([
                'user_id' => Auth::id(),
                'role_id' => Auth::user()->role_id,
                'ip_address' => $ipAddress,
                'route_name' => $routeName,
                'method' => $method,
                'description' => 'Unknown route accessed: ' . $routeName,
            ]);
        }

        return $next($request);
    }
}
