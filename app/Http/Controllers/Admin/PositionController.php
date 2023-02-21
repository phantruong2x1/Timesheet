<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Position;
use Illuminate\Support\Facades\Session;

class PositionController extends Controller
{
    public $data = [];

    //index
    public function index()
    {
        $this->data['title']= 'List of position';

        $this->data['positionsList'] = Position::paginate(10);
        // $users = User::where('votes', '>', 100)->paginate(15);
        // $users = DB::table('users')->simplePaginate(15);
        // $users = User::paginate(15);

        return view('backend.positions.list-position',$this->data);
    }

    //Return giao diện thêm
    public function getAdd()
    {
        $this->data['title']= 'Add position';
        return view('backend.positions.add-position',$this->data);
    }

    //Thêm 1 position
    public function postAdd(Request $request)
    {
        $request->validate([
            'position_name' => 'required'
        ]);
        $data = $request->all();
        $status = Position::create($data);

        if($status){
            Session::flash('alert-info', 'Thêm thành công ^^~!!!');
        }
        else {
            Session::flash('alert-danger', 'Đã có lỗi xảy ra!');
        }
        return redirect()->route('positions.index');
    }

    //Return giao diện edit
    public function getEdit(Request $request, $id =0)
    {
        $this->data['title']= 'Edit position';

        //Kiểm tra id có tồn tại
        if(!empty($id))
        {   
            $this->data['positionDetail']= Position::find($id);

            //Kiểm tra người dùng có tồn tại không
            if(!empty($this->data)){
                $request->session()->put('id',$id);   
            }else{
                Session::flash('alert-danger', 'Người dùng không tồn tại!');
                return redirect()->route('positions.index');
            }           
        }
        else{
            Session::flash('alert-danger', 'Người dùng không tồn tại!');
            return redirect()->route('positions.index');
        }
        return view('backend.positions.edit-position',$this->data);
    }

    //Edit Department
    public function postEdit(Request $request)
    {
        $id = session('id');
        $request->validate([
            'position_name' => 'required'
        ]);
        //Kiểm tra id có tồn tại
        if(empty($id))
            return redirect()->route('positions.index'); 
        
        $data=$request->all();
        $positions = Position::findOrFail($id);
        $status=$positions->fill($data)->save();

        if($status){
            Session::flash('alert-info', 'Cập nhập thành công ^^~!!!');
        }
        else {
            Session::flash('alert-danger', 'Đã có lỗi xảy ra!');
        }
        return redirect()->route('positions.index');
    }

    //Delete
    public function delete($id)
    {
        $positionDetail= Position::findOrFail($id);
        $status=$positionDetail->delete();

        if($status){
            Session::flash('alert-info', 'Xóa thành công ^^~!!!');
        }
        else {
            Session::flash('alert-danger', 'Đã có lỗi xảy ra!');
        }
        return redirect()->route('positions.index');
    }
}
