<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Staffs;
use Illuminate\Http\Request;
use App\Models\UserRole;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    public $data = [];

    //index
    public function index()
    {
        $this->data['title'] = 'List of users';
        $this->data['usersList'] = User::orderBy('created_at','desc')->simplePaginate(14);
        $this->data['staffsList'] = Staffs::all();       
        return view('backend.users.list-user', $this->data);
    }

    //Return giao diện thêm
    public function getAdd()
    {
        $this->data['title'] = 'Add user';
        $this->data['rolesList'] = UserRole::all();

        // Get Staff in Staff table, Not in User table
        $checkid = User::pluck('staff_id')->toArray();
        $this->data['staffsList'] = Staffs::whereNotIn('id',$checkid)->get();
        
        return view('backend.users.add-user',$this->data);
    }

    //Thêm 1 user
    public function postAdd(Request $request)
    {
        //Validate dữ liệu
        $request->validate([
            'user_name' => 'required|unique:users',
            'password' => 'required',
        ],[
            'user_name.required'=>'User Name không được bỏ trống!',
            'user_name.unique'=>'User Name không được trùng nhau!',
            'password.required'=>'Password không được bỏ trống!',
        ]);

        $users = new User();
        $users->role_id = $request->role_id;
        $users->staff_id = $request->staff_id;
        $users->user_name = $request->user_name;
        $users->password = Hash::make($request->password);
        $users->status = $request->status ;

        //Lưu
        $users->save();

        // Hiển thị câu thông báo 1 lần (Flash session)
        Session::flash('alert-info', 'Thêm thành công ^^~!!!');

        return redirect()->route('users.index');
    }

    //Return giao diện edit
    public function getEdit(Request $request, $id =0)
    {
        $this->data['title']= 'Edit user';
        $this->data['rolesList'] = UserRole::all();
        $this->data['staffsList'] = Staffs::all();

        //Kiểm tra id có tồn tại
        if(!empty($id))
        {   
            $this->data['userDetail']= User::find($id);

            //Kiểm tra người dùng có tồn tại không
            if(!empty($this->data)){
                $request->session()->put('id',$id);
                
                
            }else{
                Session::flash('alert-danger', 'Người dùng không tồn tại!');
                return redirect()->route('users.index');
            }
            
        }
        else{
            Session::flash('alert-danger', 'Người dùng không tồn tại!');
            return redirect()->route('users.index');
        }
        return view('backend.users.edit-user',$this->data);
    }

    //Edit User
    public function postEdit(Request $request)
    {
        $id = session('id');

        //Kiểm tra id có tồn tại
        if(empty($id))
            return redirect()->route('users.index'); 

        //Validate dữ liệu
        $request->validate([
            'user_name' => 'required',
            'password' => 'required',
        ],[
            'user_name.required'=>'User Name không được bỏ trống!',
            'password.required'=>'Password không được bỏ trống!',
        ]);

        $users = User::find($id);
        $users->role_id = $request->role_id;
        $users->staff_id = $request->staff_id;
        $users->user_name = $request->user_name;
        $users->password = Hash::make($request->password);
        $users->status = $request->status ;

        //Lưu
        $users->save();

        Session::flash('alert-info', 'Sửa thành công!');
        return redirect()->route('users.index');
    }

    //Delete
    public function delete($id)
     {
        if(!empty($id)){

            $userDetail= User::find($id);

            //Kiểm tra người dùng có tồn tại không
            if(!empty($userDetail)){
                $userDetail->delete();
                
            }else{
                Session::flash('alert-danger', 'Người dùng không tồn tại!');
                return redirect()->route('users.index');
            }

        }else{
            Session::flash('alert-danger', 'Người dùng không tồn tại!');
            return redirect()->route('users.index');
        }
        Session::flash('alert-info', 'Xóa thành công!');
        return redirect()->route('users.index');
    }
}