<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Staffs;
use App\Models\Position;
use App\Models\Department;

class StaffsController extends Controller
{
    //Khởi tạo biến staffs
    private $staffs;
    public $data = [];

    public function __construct()
    {
        $this->staffs = new Staffs();
        // $this->departments = new Department(); 
    }

    //index
    public function index(Request $request)
    {
        $this->data['title']= 'List of staff';
        $filter = [];
        
        $this->data['staffsList'] = Staffs::all();
        $this->data['positionList'] = Position::all();

        // lọc dữ liệu 
        if(!empty($request->position_id)){
            $filter[] = ['position_id','=',$request->position_id];
        }
        if(!empty($request->status)){
            $filter[] = ['status','=',$request->status];
        }

        $this->data['staffsList']=Staffs::where($filter)->paginate(15);

        return view('backend.staffs.list-staff',$this->data);
    }

    //Return giao diện add
    public function getAdd()
    {
        $this->data['title']= 'Add staff';

        $this->data['positionList'] = Position::all();
        $this->data['departmentList'] = Department::all();

        return view('backend.staffs.add-staff',$this->data);
    }

    //Thêm một nhân viên
    public function postAdd(Request $request)
    {
        //Validate dữ liệu
        $request->validate([
            'id' => 'required|unique:staff',
            'full_name' => 'required',
            'email' => 'required|unique:staff',
        ],[
            'id.required'=>'Mã nhân viên không được bỏ trống!',
            'id.unique'=>'Mã nhân viên đã tồn tại!',
            'full_name.required' => 'Tên nhân viên không được bỏ trống!',
            'email.required'=>'Email không được bỏ trống!',
            'email.unique'=>'Email đã tồn tại!',
        ]);

        //Lấy dữ liệu
        $dataInsert = [
            $request -> id,
            $request -> full_name,
            $request -> birthday,
            $request -> gender,
            $request -> tax_code,
            $request -> phone_number,
            $request -> email,
            $request -> address,
            $request -> email_company,
            $request -> begin_time,
            $request -> end_time,
            $request -> official_time,
            $request -> type,
            $request -> department_id,
            $request -> position_id,
            $request -> status,
            date('Y-m-d H:i:s')      
        ];

        //Thêm dữ liệu vào database
        $this->staffs->addStaff($dataInsert);

        return redirect()->route('staff.index');
    }

    //return giao diện edit nhân viên
    public function getEdit(Request $request, $id=0)
    {
        $title = 'Edit staff';
        $this->data['positionList'] = Position::all();
        $this->data['departmentList'] = Department::all();

        //Kiểm tra id có tồn tại
        if(!empty($id)){
            $staffDetail = $this->staffs->getDetail($id);

            //Kiểm tra người dùng có tồn tại không
            if(!empty($staffDetail[0])){
                $request->session()->put('id',$id);
                $staffDetail = $staffDetail[0];
            }else{
                return redirect()->route('staff.index');
            }
        }
        else{
            return redirect()->route('staff.index');
        }
        
        return view('backend.staffs.edit-staff',compact('title','staffDetail'),$this->data);
    }

    //edit nhân viên
    public function postEdit(Request $request)
    {
        $id = session('id');

        //Kiểm tra id có tồn tại không
        if(empty($id))
            return redirect()->route('staff.index'); 

        //Validate dữ liệu
        $request->validate([
            'id' => 'required|unique:staff,id,'.$id,
            'full_name' => 'required',
            'email' => 'required|unique:staff,email,'.$id,
        ],[
            'id.required'=>'Mã nhân viên không được bỏ trống!',
            'id.unique'=>'Mã nhân viên đã tồn tại!',
            'full_name.required' => 'Tên nhân viên không được bỏ trống!',
            'email.required'=>'Email không được bỏ trống!',
            'email.unique'=>'Email đã tồn tại!',    
        ]);

        //Lấy dữ liệu
        $dataUpdate = [
            $request -> id,
            $request -> full_name,
            $request -> birthday,
            $request -> gender,
            $request -> tax_code,
            $request -> phone_number,
            $request -> email,
            $request -> address,
            $request -> email_company,
            $request -> begin_time,
            $request -> end_time,
            $request -> official_time,
            $request -> type,
            $request -> department_id,
            $request -> position_id,
            $request -> status,
            date('Y-m-d H:i:s')      
        ];

        //Update dữ liêu vào database
        $this->staffs->updateStaff($dataUpdate,$id);

        return redirect()->route('staff.index');
        
    }

    //Xóa nhân viên
    public function delete($id)
    {
        if(!empty($id)){

            $staffDetail = $this->staffs->getDetail($id);

            //Kiểm tra người dùng có tồn tại không
            if(!empty($staffDetail[0])){
                $this->staffs->deleteStaff($id);
                
            }else{
                return redirect()->route('staff.index');
            }

        }else{
            return redirect()->route('staff.index');
        }
        return redirect()->route('staff.index');
    }


}
