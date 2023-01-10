<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
    public function authenticated(Request $request)
    {
        //Lấy role_id từ tài khoản đăng nhập
        $role_id = Auth::user()->role_id;

        //Lấy role_name từ tài khoản đăng nhập
        $role = DB::table('user_role')
                ->where('id', $role_id)
                ->value('role_name');
        
        //Nếu chưa có tài khoản đăng nhập->return đăng nhập
        if(!Auth::user()){
            return redirect()->route('login');
        }
        //Nếu tài khoản có role_name là admin
        else if($role =='admin' ){
            return redirect()->route('admin-dashboard');
        }
        //Nếu tài khoản có role_name là user
        else if($role =='client'){
            return redirect()->route('client-dashboard');
        }
        //Nếu tài khoản có role_name là host
        // else if($role =='host'){
        //     return redirect()->route('client-dashboard');
        // }
        
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login'); 
    }

}
