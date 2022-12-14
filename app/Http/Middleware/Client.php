<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Client
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        //Lấy role_id của người dùng
        $role_id = Auth::user()->role_id;

        //Lấy role_name
        $role_name = DB::table('user_role')
        ->where('id',$role_id)
        ->value('role_name');

        //Kiểm tra nếu role_name với 'staff'
        if ($role_name == 'client'){

        }else{
            //Nếu role_name != 'staff'->đăng xuất && chuyển hướng trang
            Auth::logout();
            return redirect()->route('denied');
        }
        return $next($request);
    }
}
