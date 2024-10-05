<?php

namespace App\Http\Controllers\User_log;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User_log\UnknownUserLog;
use App\Models\User_log\UserLog;
use App\Models\User_log\UserLogList;

class UserLogController extends Controller
{
    public function createLog(Request $request)
    {
        $routeName = $request->route()->getName();
        $userLogList = UserLogList::where('route_name', $routeName)->first();

        if (!$userLogList) {
           $userLogList->description = 'Not found route!';
           $userLogList->route_name = $routeName;
           
           return redirect()->route('dashboard');
        }else{
            UserLog::create([
                'user_id' => Auth::user()->id,
                'role_id' => Auth::user()->role_id,
                'ip_address' => $request->ip(), 
                'user_log_category_id' => $userLogList->user_log_category_id,
                'user_log_list_id' => $userLogList->id, 
                'description' => $userLogList->description, // Deskripsi diambil dari user_log_list, bukan user_log_categories
            ]);
            UnknownUserLog::create([
                'user_id' => Auth::id(),
                'role_id' => Auth::user()->role_id,
                'ip_address' => $request->ip(),
                'route_name' => $routeName,
                'description' => 'Unknown route accessed: ' . $routeName,
            ]);
            
        }
    }
}
