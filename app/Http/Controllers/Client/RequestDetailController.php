<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Timesheet;
use App\Models\RequestDetail;
use App\Models\Staffs;
use App\Http\Controllers\Admin\TimesheetController;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class RequestDetailController extends Controller
{
    public $data =[];
    
    public function __construct(){
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }
    public function index(Request $request)
    {
        $this->data['userDetail'] = Auth::user();
        $this->data['userTimesheet'] = Timesheet::where('staff_id',Auth::user()->staff_id)
        ->where('date',date('d-m-Y'))
        ->first();
        $this->data['dt'] = date('d-m-Y');
        //lọc dữ liệu 
        $filter[] = ['staff_id','=', Auth::user()->staff_id];
        if(!empty($request->request_type)){
            $filter[] = ['request_type','=',$request->request_type];
        }
        if(empty($request->date_filter)){
            $dateFilter = date('m-Y');
        }
        else{
            $millisecond = strtotime('1-'.$request->date_filter.'');
            $dateFilter = date('m-Y',$millisecond);
        }
        $userRequest = RequestDetail::where($filter)
                                    ->orderBy('updated_at','desc')->get();
        foreach($userRequest as $key=>$item){
            if($dateFilter == date('m-Y',strtotime($item->timesheet_date))){
                $this->data['userListRequest'][$key] = [
                    'id' => $item->id,
                    'request_type' => $item->request_type,
                    'timesheet_date' => $item->timesheet_date,
                    'time' => $item->time,
                    'reason' => $item->reason,
                    'time_respond' => $item->time_respond,
                    'status' => $item->status,
                    'created_at' => $item->created_at,
                ];
            }
        }
        // dd(date('m-Y',strtotime('2-12-2022')));
        return view('frontend.list-request',$this->data);
    }
    //option forget
    public function forget(Request $request, $id)
    {
        $this->data['title'] = 'Forget';
        $request->session()->put('id',$id);
        $timesheetDetail = Timesheet::find($id);
        $timesheetData = [
            'id' => $timesheetDetail->id,
            'date' => date('Y-m-d', strtotime($timesheetDetail->date)),
            'first_checkin' => date('H:i:s', $timesheetDetail->first_checkin/1000),

        ];
        return response()->json($timesheetData);
    }
    public function postForget(Request $request)
    {
        $id = session('id');
        //validate data
        $request->validate([
            'last_checkout' => 'required',
        ]);

        $timesheet = new TimesheetController();
        $staffDetail = Staffs::find(Auth::user()->staff_id);
        //Update timesheet
        $timesheetDetail = Timesheet::find($id);
        $timesheetDetail->first_checkin = strtotime($request->first_checkin)*1000;
        $timesheetDetail->last_checkout = strtotime($request->last_checkout)*1000;
        $timesheetDetail->working_hour = $timesheet->getWorkingHour($timesheetDetail->first_checkin, $timesheetDetail->last_checkout);
        $timesheetDetail->overtime = $timesheet->getOverTime($timesheetDetail->working_hour);
        if($staffDetail->shift == 'Ca 2')
            $timesheetDetail->status = $timesheet->getStatusShift2($timesheetDetail->first_checkin, $timesheetDetail->last_checkout);
        else
            $timesheetDetail->status = $timesheet->getStatusShift1($timesheetDetail->first_checkin, $timesheetDetail->last_checkout);
                        

        //Create request detail
        $requestDetail = new RequestDetail();
        $requestDetail->staff_id = Auth::user()->staff_id;
        $requestDetail->request_type = 'Update Checkout';
        $requestDetail->timesheet_id = $timesheetDetail->id;
        $requestDetail->timesheet_date = $timesheetDetail->date;
        $requestDetail->time = date('H:i:s',$timesheetDetail->last_checkout/1000);
        $requestDetail->reason = $request->reason;
        $status = $requestDetail->save();
        
        $timesheetDetail->save();

        // Hiển thị câu thông báo 1 lần (Flash session)
        if($status)
            Session::flash('toast-success', 'Cập nhập giờ tan làm thành công!');
        else
            Session::flash('toast-error', 'Đã có lỗi xảy ra! Xin vui lòng kiểm tra lại.');

        return redirect()->back();
    }

    //option please be late
    public function beLate($date)
    {
        $dateBeLate = date('Y-m-d H:i:s',strtotime($date));
        return response()->json($dateBeLate);
    }
    public function postBeLate(Request $request)
    {
        //validate data
        $request->validate([
            'from' => 'required',
        ]);
        //Create request detail
        $requestDetail = new RequestDetail();
        $requestDetail->staff_id = Auth::user()->staff_id;
        $requestDetail->request_type = 'Please Be Late';
        $requestDetail->timesheet_date = date('d-m-Y',strtotime($request->from));
        $requestDetail->time = date('H:i:s',strtotime($request->from));
        $requestDetail->reason = $request->reason;
        $status = $requestDetail->save();
 
        // Hiển thị câu thông báo 1 lần (Flash session)
        if($status)
            Session::flash('toast-success', 'Gửi yêu cầu xin đi muộn thành công!');
        else
            Session::flash('toast-error', 'Đã có lỗi xảy ra! Xin vui lòng kiểm tra lại.');

        return redirect()->back();
    }

    //option please come back soon
    public function comeBackSoon($date)
    {
        $dateComeBackSoon = date('Y-m-d H:i:s',strtotime($date));
        return response()->json($dateComeBackSoon);
    }
    public function postComeBackSoon(Request $request)
    {
        //validate data
        $request->validate([
            'to' => 'required',
        ]);
        //Create request detail
        $requestDetail = new RequestDetail();
        $requestDetail->staff_id = Auth::user()->staff_id;
        $requestDetail->request_type = 'Please Come Back Soon';
        $requestDetail->timesheet_date = date('d-m-Y',strtotime($request->to));
        $requestDetail->time = date('H:i:s',strtotime($request->to));
        $requestDetail->reason = $request->reason;
        $status = $requestDetail->save();
 
        // Hiển thị câu thông báo 1 lần (Flash session)
        if($status)
            Session::flash('toast-success', 'Gửi yêu cầu xin về sớm thành công!');
        else
            Session::flash('toast-error', 'Đã có lỗi xảy ra! Xin vui lòng kiểm tra lại.');

        return redirect()->back();
    }
    //option take a break
    public function takeABreak($date)
    {
        $dateTakeABreak = date('Y-m-d',strtotime($date));
        return response()->json($dateTakeABreak);
    }
    public function postTakeABreak(Request $request)
    {
        //validate data
        $request->validate([
            'from' => 'required',
        ]);
        //Create request detail
        $requestDetail = new RequestDetail();
        $requestDetail->staff_id = Auth::user()->staff_id;
        $requestDetail->request_type = 'Take A Break';
        $requestDetail->timesheet_date = date('d-m-Y',strtotime($request->from));
        $requestDetail->time = date('H:i:s',strtotime($request->from));
        $requestDetail->reason = $request->reason;
        $status = $requestDetail->save();
 
        // Hiển thị câu thông báo 1 lần (Flash session)
        if($status)
            Session::flash('toast-success', 'Gửi yêu cầu nghỉ phép thành công!');
        else
            Session::flash('toast-error', 'Đã có lỗi xảy ra! Xin vui lòng kiểm tra lại.');

        return redirect()->back();
    }
    //make orders
    public function makeOrder($id,$date)
    {
        if($id == -1){
            $this->data['listMakeOrder'] = Timesheet::orderBy('date', 'desc')->first();
            $this->data['date'] = strtotime($date)*1000;
            $this->data['display'] = 'none';
        }
        else{
            $this->data['listMakeOrder'] = Timesheet::find($id);    
            $this->data['date'] = strtotime($date)*1000;
            $this->data['display'] = '';
        }
        
        return view('frontend.options.make-order',$this->data);
    }
    public function destroy($id)
    {
        $requestDetail= RequestDetail::find($id);
        
        //Update timesheet
        if($requestDetail->request_type == 'Update Checkout'){
            $timesheetDetail = Timesheet::findOrFail($requestDetail->timesheet_id);
            $timesheetDetail->last_checkout = null;
            $timesheetDetail->working_hour = null;
            $timesheetDetail->overtime = null;
            $timesheetDetail->status = null;
            $timesheetDetail->save();
        }
        $status = $requestDetail->delete();

        // Hiển thị câu thông báo 1 lần (Flash session)
        if($status)
            Session::flash('toast-info', 'Xóa yêu cầu thành công!');
        else
            Session::flash('toast-error', 'Đã có lỗi xảy ra! Xin vui lòng kiểm tra lại.');
     
        return redirect()->back();
    }
}
