<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Staffs;
use App\Models\Position;
use App\Models\Department;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    public $data = [];

    public function getStaff()
    {
        $this->data['staffDetail'] = Staffs::find(Auth::user()->staff_id);
        $this->data['positionList'] = Position::all();
        $this->data['departmentList'] = Department::all();
        return view('frontend.settings.get-staff',$this->data);
    }
    public function updateStaff(Request $request)
    {
         //Validate dữ liệu
         $request->validate([
            'id' => 'required|unique:staff,id,'.Auth::user()->staff_id,
            'full_name' => 'required',
            'email' => 'required|unique:staff,email,'.Auth::user()->staff_id,
        ],[
            'id.required'=>'Mã nhân viên không được bỏ trống!',
            'id.unique'=>'Mã nhân viên đã tồn tại!',
            'full_name.required' => 'Tên nhân viên không được bỏ trống!',
            'email.required'=>'Email không được bỏ trống!',
            'email.unique'=>'Email đã tồn tại!',    
        ]);
        //Update
        $staffs = Staffs::find(Auth::user()->staff_id);
        $staffs->id             = $request->id;
        $staffs->full_name      = $request->full_name;
        $staffs->birthday       = $request->birthday;
        $staffs->gender         = $request->gender;
        $staffs->tax_code       = $request->tax_code;
        $staffs->phone_number   = $request->phone_number;
        $staffs->email          = $request->email;
        $staffs->address        = $request->address;
        $staffs->email_company  = $request->email_company;
        $staffs->begin_time     = $request->begin_time;
        $staffs->end_time       = $request->end_time;
        $staffs->official_time  = $request->official_time;
        $staffs->type           = $request->type;
        $staffs->department_id  = $request->department_id;
        $staffs->position_id    = $request->position_id;
        $staffs->shift          = $request->shift;

        $staffs->save();
        Session::flash('alert-info', 'Cập nhập thành công!');
        return redirect()->route('client-dashboard');
    }
    public function changePassword()
    {
        $this->data['title']= 'Change Password';
        $this->data['userDetail'] = Auth::user();

        return view('frontend.settings.change-password',$this->data);
    }
    public function updatePassword(Request $request)
    {
        //Validate dữ liệu
        $request->validate([
            'password' => 'required',
        ],[
            'password.required'=>'Password không được bỏ trống!',
        ]);

        $users = User::find(Auth::user()->id);
        $users->password = Hash::make($request->password);
        //Lưu
        $users->save();
        Session::flash('alert-info', 'Đổi mật khẩu thành công!');
        return redirect()->route('client-dashboard');
    }
}
