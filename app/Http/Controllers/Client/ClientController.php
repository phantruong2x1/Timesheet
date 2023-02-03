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
            $dateFilter = date('d-m-Y',mktime(0, 0, 0, $monthFilter, $i, $yearFilter));
            $millisecondWeekday = strtotime($dateFilter);
            $weekday = getdate($millisecondWeekday);
            if($this->getWeekday($weekday['weekday'])== 'Thứ 7'){
                $this->data['colorWeekday'] = '#CCFFCC';
            }
            else if($this->getWeekday($weekday['weekday']) == 'CN')
                $this->data['colorWeekday'] = '#FFCCFF';
            else if($dateFilter == date('d-m-Y'))
                $this->data['colorWeekday'] = '#FFFF66';
            else
                $this->data['colorWeekday'] = '';

            $this->data['userListTimesheet'][$i] = [
                'date' => $dateFilter, 
                'weekday' => $this->getWeekday($weekday['weekday']),
                'colorWeekday' => $this->data['colorWeekday']
            ]; 
            foreach($listTimesheet as $timesheetDetail){
                if($dateFilter == date('d-m-Y',$timesheetDetail->date/1000)){
                   
                    $this->data['userListTimesheet'][$i] = [
                        'id' => $timesheetDetail->id,
                        'date' => $dateFilter, 
                        'weekday' => $this->getWeekday($weekday['weekday']),
                        'colorWeekday' => $this->data['colorWeekday'],
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
        // dd($time['weekday'] );                     
        // dd($this->getWeekday($time['weekday']));  
        return view('frontend.dashboard', $this->data);
    }
    //Lấy thứ trong tuần
    public function getWeekday($weekday)
    {
        switch ($weekday) {
            case "Monday":
                return 'Thứ 2';
                break;
            case 'Tuesday':
                return 'Thứ 3';
                break;
            case 'Wednesday':
                return 'Thứ 4';
                break;
            case "Thursday":
                return 'Thứ 5';
                break;
            case 'Friday':
                return 'Thứ 6';
                break;
            case 'Saturday':
                return 'Thứ 7';
                break;
            case 'Sunday':
                return 'CN';
                break;
        }  
    }
}

