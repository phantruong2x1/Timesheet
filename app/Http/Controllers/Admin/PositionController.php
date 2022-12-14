<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Position;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PositionController extends Controller
{
    public $data = [];

    //index
    public function index()
    {
        $this->data['title']= 'List of position';

        $this->data['positionsList'] = Position::simplePaginate(10);
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
        $positions = new Position;
        $positions->position_name = $request->position_name;
        $positions->position_desc = $request->position_desc;

        //Lưu
        $positions->save();

        // Hiển thị câu thông báo 1 lần (Flash session)
        Session::flash('alert-info', 'Thêm thành công ^^~!!!');

        return redirect()->route('positions.index');
    }

    //Return giao diện thêm
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

        //Kiểm tra id có tồn tại
        if(empty($id))
            return redirect()->route('positions.index'); 

        $positions = Position::find($id);
        $positions->position_name = $request->position_name;
        $positions->position_desc = $request->position_desc;

        //Lưu
        $positions->save();

        Session::flash('alert-info', 'Sửa thành công!');
        return redirect()->route('positions.index');
    }

    //Delete
    public function delete($id)
    {
        if(!empty($id)){

            $positionDetail= Position::find($id);

            //Kiểm tra người dùng có tồn tại không
            if(!empty($positionDetail)){
                $positionDetail->delete();
                
            }else{
                Session::flash('alert-danger', 'Người dùng không tồn tại!');
                return redirect()->route('positions.index');
            }

        }else{
            Session::flash('alert-danger', 'Người dùng không tồn tại!');
            return redirect()->route('positions.index');
        }
        Session::flash('alert-info', 'Xóa thành công!');
        return redirect()->route('positions.index');
    }
}
