<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RequestDetail;
use App\Models\Timesheet;
use Illuminate\Support\Facades\Session;
use Ramsey\Uuid\Type\Time;
use App\Models\Staffs;

class RequestDetailController extends Controller
{
    public $data =[];
    
    public function __construct(){
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }

    public function index(Request $request)
    {
        $this->data['title'] = 'Request Detail';
        $this->data['listRequestPeding'] = RequestDetail::where('status', null)->orderBy('created_at','desc')->paginate(15);

        $this->data['staffsList'] = Staffs::all();   
        $filter[] = ['status','!=', 'null'];
        // lọc dữ liệu 
        if(!empty($request->staff_id)){
            $filter[] = ['staff_id','=',$request->staff_id];
        }
        $this->data['listRequestHistory'] = RequestDetail::where($filter)->orderBy('updated_at','desc')->paginate(15);
        return view('backend.requests.list-request',$this->data);
    }

    public function updateAccept(Request $request, $id)
    {
        $requestDetail = RequestDetail::find($id);
        //Update request detail
        $requestDetail->time_respond = date('Y-m-d H:i:s');
        $requestDetail->status = '1';
        $requestDetail->save();
 
        // Hiển thị câu thông báo 1 lần (Flash session)
        Session::flash('alert-info', 'Accept data successfully!');

        return redirect()->route('requests.index');
    }
    public function updateDenied(Request $request, $id)
    {
        $requestDetail = RequestDetail::find($id);
        //Update request detail
        $requestDetail->time_respond = date('Y-m-d H:i:s');
        $requestDetail->status = '0';
        $requestDetail->save();

        //Update timesheet
        if($requestDetail->request_type == 'Update Checkout'){
            $timesheetDetail = Timesheet::find($requestDetail->timesheet_id);
            $timesheetDetail->last_checkout = null;
            $timesheetDetail->working_hour = null;
            $timesheetDetail->overtime = null;
            $timesheetDetail->status = null;
            $timesheetDetail->save();
        }
 
        // Hiển thị câu thông báo 1 lần (Flash session)
        Session::flash('alert-info', 'Denied data successfully!');

        return redirect()->route('requests.index');
    }
    public function destroy($id)
    {
        $requestDetail= RequestDetail::find($id);
        $requestDetail->delete();
        Session::flash('alert-info', 'Xóa thành công!');
        return redirect()->route('admin-dashboard');
    }
}
