<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Staffs;
use App\Models\Position;
use App\Models\Department;
use App\Models\Feedback;
use App\Models\User;
use App\Models\UserRole;
use App\Rules\MatchOldPassword;
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
        $data = $request->all();
        //Update
        $staffs = Staffs::find(Auth::user()->staff_id);
        $status = $staffs->fill($data)->save();
        if($status){
            Session::flash('alert-info', 'Cập nhập thành công!');
        }
        else{
            Session::flash('alert-danger', 'Đã có lỗi xảy ra!');
        }
        
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
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ],[
            'password.required'=>'Password không được bỏ trống!',
        ]);

        User::find(Auth::user()->id)->update(['password'=> Hash::make($request->new_password)]);
        Session::flash('alert-info', 'Đổi mật khẩu thành công!');
        return redirect()->route('client-dashboard');
    }

    public function createFeedback(Request $request)
    {
        $feedback = new Feedback;
        $data = $request->all();
        $data['staff_id'] = Auth::user()->staff_id;
        $feedback->createFeedback($data);

    }
}
