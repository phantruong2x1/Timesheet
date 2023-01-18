<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Timesheet;
use App\Models\RequestDetail;
use App\Http\Controllers\Admin\TimesheetController;
use Illuminate\Support\Facades\Session;

class RequestDetailController extends Controller
{
    public $data =[];
    //option forget
    public function __construct(){
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }
    
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
        $timesheetDetail->last_checkout = $request->last_checkout;
        $timesheetDetail->working_hour = $timesheet->getWorkingHour($timesheetDetail->first_checkin, $timesheetDetail->last_checkout);
        $timesheetDetail->overtime = $timesheet->getOverTime($timesheetDetail->working_hour);
        $timesheetDetail->status = $timesheet->getStatus($timesheetDetail->first_checkin, $timesheetDetail->last_checkout);
        $timesheetDetail->save();

        //Create request detail
        $requestDetail = new RequestDetail();
        $requestDetail->request_id = 1;
        $requestDetail->timesheet_id = $timesheetDetail->id;
        $requestDetail->reason = $timesheetDetail->reason;
        $requestDetail->save();

        // Hiển thị câu thông báo 1 lần (Flash session)
        Session::flash('alert-info', 'Insert data successfully!');

        return view('frontend.options.forget',$this->data);
    }

    //option please be late
    public function beLate()
    {
        return view('frontend.options.please-be-late',$this->data);
    }

    //option please come back soon
    public function comeBackSoon()
    {
        return view('frontend.options.please-come-back-soon',$this->data);
    }

    //option take a break
    public function takeABreak()
    {
        return view('frontend.options.take-a-break',$this->data);
    }
}
