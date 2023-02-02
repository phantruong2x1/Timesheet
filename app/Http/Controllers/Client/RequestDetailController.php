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
    
    //option forget
    public function forget($id)
    {
        $this->data['title'] = 'Forget';
        $this->data['timesheetDetail'] = Timesheet::find($id);
        
        return view('frontend.options.forget',$this->data);
    }
    public function postForget(Request $request, $id)
    {
        //validate data
        $request->validate([
            'last_checkout' => 'required',
        ]);

        $timesheet = new TimesheetController();
        //Update timesheet
        $timesheetDetail = Timesheet::find($id);
        $timesheetDetail->first_checkin = strtotime($request->first_checkin)*1000;
        $timesheetDetail->last_checkout = strtotime($request->last_checkout)*1000;
        $timesheetDetail->working_hour = $timesheet->getWorkingHour($timesheetDetail->first_checkin, $timesheetDetail->last_checkout);
        $timesheetDetail->overtime = $timesheet->getOverTime($timesheetDetail->working_hour);
        $timesheetDetail->status = $timesheet->getStatus($timesheetDetail->first_checkin, $timesheetDetail->last_checkout);

        //Create request detail
        $requestDetail = new RequestDetail();
        $requestDetail->staff_id = Auth::user()->staff_id;
        $requestDetail->request_type = 'Update Checkout';
        $requestDetail->timesheet_id = $timesheetDetail->id;
        $requestDetail->reason = $request->reason;
        $requestDetail->save();
        
        $timesheetDetail->save();

        // Hiển thị câu thông báo 1 lần (Flash session)
        Session::flash('alert-info', 'Insert data successfully!');

        return redirect()->route('client-dashboard');
    }

    //option please be late
    public function beLate()
    {
        return view('frontend.options.please-be-late',$this->data);
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
        $requestDetail->request_type = 'Please be late';
        $requestDetail->from = strtotime($request->from)*1000;
        $requestDetail->reason = $request->reason;
        $requestDetail->save();
 
        // Hiển thị câu thông báo 1 lần (Flash session)
        Session::flash('alert-info', 'Insert data successfully!');

        return redirect()->route('client-dashboard');
    }

    //option please come back soon
    public function comeBackSoon()
    {
        return view('frontend.options.please-come-back-soon',$this->data);
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
        $requestDetail->request_type = 'Please come back soon';
        $requestDetail->to = strtotime($request->to)*1000;
        $requestDetail->reason = $request->reason;
        $requestDetail->save();
 
        // Hiển thị câu thông báo 1 lần (Flash session)
        Session::flash('alert-info', 'Insert data successfully!');

        return redirect()->route('client-dashboard');
    }
    //option take a break
    public function takeABreak()
    {
        return view('frontend.options.take-a-break',$this->data);
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
        $requestDetail->request_type = 'Take a break';
        $requestDetail->from = strtotime($request->from)*1000;
        $requestDetail->reason = $request->reason;
        $requestDetail->save();
 
        // Hiển thị câu thông báo 1 lần (Flash session)
        Session::flash('alert-info', 'Insert data successfully!');

        return redirect()->route('client-dashboard');
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
        $requestDetail->delete();
        Session::flash('alert-info', 'Xóa thành công!');
        return redirect()->route('client-dashboard');
    }
}
