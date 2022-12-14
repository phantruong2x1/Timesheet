<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    public $data = [];

    //index
    public function index()
    {
        $this->data['title']= 'List of department';

        $this->data['departmentsList'] = DB::table('departments')->orderBy('created_at','DESC')->get();

        return view('backend.departments.list-department',$this->data);
    }

    //Return giao diện thêm
    public function getAdd()
    {
        $this->data['title']= 'Add department';
        return view('backend.departments.add-department',$this->data);
    }

    //Thêm 1 department
    public function postAdd(Request $request)
    {
        $departments = new Department;
        $departments->department_name = $request->department_name;
        $departments->department_desc = $request->department_desc;

        //Lưu
        $departments->save();

        return redirect()->route('departments.index');
    }

    //Return giao diện thêm
    public function getEdit(Request $request, $id =0)
    {
        $this->data['title']= 'Edit department';

        //Kiểm tra id có tồn tại
        if(!empty($id))
        {   
            $this->data['departmentDetail']= Department::find($id);
            //Kiểm tra người dùng có tồn tại không
            if(!empty($this->data)){
                $request->session()->put('id',$id);
                
                
            }else{
                return redirect()->route('departments.index');
            }
            
        }
        else{
            return redirect()->route('departments.index');
        }
        return view('backend.departments.edit-department',$this->data);
    }

    //Edit Department
    public function postEdit(Request $request)
    {
        $id = session('id');

        //Kiểm tra id có tồn tại
        if(empty($id))
            return redirect()->route('departments.index'); 

        $departments = Department::find($id);
        $departments->department_name = $request->department_name;
        $departments->department_desc = $request->department_desc;

        //Lưu
        $departments->save();

        return redirect()->route('departments.index');
    }

    //Delete
    public function delete($id)
    {
        if(!empty($id)){

            $departmentDetail = Department::find($id);

            //Kiểm tra người dùng có tồn tại không
            if(!empty($departmentDetail)){
                $departmentDetail->delete();
                
            }else{
                return redirect()->route('departments.index');
            }

        }else{
            return redirect()->route('departments.index');
        }
        return redirect()->route('departments.index');
    }                                                                              
}
