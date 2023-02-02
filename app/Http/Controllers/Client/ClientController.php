<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Timesheet;
use App\Models\RequestDetail;

class ClientController extends Controller
{
    public $data = [];
    
    public function __construct(){
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }

    public function index(Request $request)
    {
        $this->data['userDetail'] = Auth::user();
        //Lấy 1 dữ liệu timesheet mới nhất của Auth::user
        $this->data['userTimesheet'] =  Timesheet::where('staff_id',Auth::user()->staff_id)
                                        ->orderBy('date', 'DESC')
                                        ->first();

        //Lấy toàn bộ dữ liệu timesheet của Auth::user
        if(empty($request->date_filter)){
            $monthFilter = date('m');
            $yearFilter = date('Y');
        }
        else{
            $millisecond = strtotime('1-'.$request->date_filter.'');
            $monthFilter = date('m',$millisecond);
            $yearFilter = date('Y',$millisecond);
        }
        $dayOfMonth = cal_days_in_month(CAL_GREGORIAN, $monthFilter, $yearFilter);
        $listTimesheet = Timesheet::where('staff_id',Auth::user()->staff_id)->orderBy('date','desc')->get();

        for($i=1; $i<=$dayOfMonth; $i++){
            $this->data['userListTimesheet'][$i] = [
                'date' => date('d-m-Y',mktime(0, 0, 0, $monthFilter, $i, $yearFilter)), 
            ]; 
            foreach($listTimesheet as $timesheetDetail){
                if(date('d-m-Y',mktime(0, 0, 0, $monthFilter, $i, $yearFilter)) == date('d-m-Y',$timesheetDetail->date/1000)){
                    $this->data['userListTimesheet'][$i] = [
                        'id' => $timesheetDetail->id,
                        'date' => date('d-m-Y',mktime(0, 0, 0, $monthFilter, $i, $yearFilter)), 
                        'first_checkin' => $timesheetDetail->first_checkin,
                        'last_checkout' => $timesheetDetail->last_checkout,
                        'working_hour' => $timesheetDetail->working_hour,
                        'overtime' => $timesheetDetail->overtime,
                        'leave_status' => $timesheetDetail->leave_status,
                        'status' => $timesheetDetail->status
                    ];            
                    break;
                }
            }
        }
                                        
        //Lấy toàn bộ dữ liệu request detail của Auth::user
        $this->data['userListRequest'] = RequestDetail::where('staff_id',Auth::user()->staff_id)
                                        ->orderBy('updated_at','desc')->paginate(10);
                                        
        $this->data['dt'] = date('d-m-Y');
        return view('frontend.dashboard', $this->data);
    }
}

