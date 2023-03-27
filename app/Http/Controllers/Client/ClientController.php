<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Timesheet;
use App\Models\RequestDetail;
use App\Models\Statisticals;

class ClientController extends Controller
{
    public $data = [];
    
    public function __construct(){
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }

    public function index(Request $request)
    {
        //Lấy toàn bộ dữ liệu timesheet của Auth::user
        // if(empty($request->date_filter)){
        //     $monthFilter = date('m');
        //     $yearFilter = date('Y');
        // }
        // else{
        //     $millisecond = strtotime('1-'.$request->date_filter.'');
        //     $monthFilter = date('m',$millisecond);
        //     $yearFilter = date('Y',$millisecond);
        // }

        // $dayOfMonth = cal_days_in_month(CAL_GREGORIAN, $monthFilter, $yearFilter);
        // $listTimesheet = Timesheet::where('staff_id',Auth::user()->staff_id)->orderBy('date','desc')->get();

        // for($i=1; $i<=$dayOfMonth; $i++){
        //     $dateFilter = date('d-m-Y',mktime(0, 0, 0, $monthFilter, $i, $yearFilter));
        //     $millisecondWeekday = strtotime($dateFilter);
        //     $weekday = getdate($millisecondWeekday);
        //     $weekdayV = $this->getWeekday($weekday['weekday']);
        //     $colorWeekday = $this->getColorWeekday($weekday);

        //     if($dateFilter == date('d-m-Y'))
        //         $colorWeekday = '#FFFF66';
                
        //     $this->data['userListTimesheet'][$i] = [
        //         'date' => $dateFilter, 
        //         'weekday' => $weekdayV,
        //         'colorWeekday' => $colorWeekday
        //     ]; 
        //     foreach($listTimesheet as $timesheetDetail){
        //         if($dateFilter == $timesheetDetail->date){
                   
        //             $this->data['userListTimesheet'][$i] = [
        //                 'id' => $timesheetDetail->id,
        //                 'date' => $dateFilter, 
        //                 'weekday' => $weekdayV,
        //                 'colorWeekday' => $colorWeekday,
        //                 'first_checkin' => $timesheetDetail->first_checkin,
        //                 'last_checkout' => $timesheetDetail->last_checkout,
        //                 'working_hour' => $timesheetDetail->working_hour,
        //                 'overtime' => $timesheetDetail->overtime,
        //                 'leave_status' => $timesheetDetail->leave_status,
        //                 'status' => $timesheetDetail->status
        //             ];            
        //             break;
        //         }
        //     }
        // }
        $this->data['countDaysWork'] = $this->countWeekdaysInMonth(date('m'), date('Y'));
        $this->data['statisticalDeatil'] = Statisticals::where('staff_id', Auth::user()->staff_id)->where('time',date('m-Y'))->first();
        $this->data['userDetail'] = Auth::user();
        $this->data['userTimesheet'] =  Timesheet::where('staff_id',Auth::user()->staff_id)
        ->orderBy('date', 'DESC')
        ->first();
        $this->data['dt'] = date('d-m-Y');

        $userListTimesheet[] = [];
        $dateFilter = $this->getWeekdayNow(time());

        $listTimesheet = Timesheet::where('staff_id',Auth::user()->staff_id)->orderBy('id','desc')->get();
        for($i=0; $i<7; $i++){
            $weekday = $this->getWeekday($dateFilter[$i]);
            $colorWeekday = $this->getColorWeekday($dateFilter[$i]);

            $userListTimesheet[$i] = [
                'date' => $dateFilter[$i],
                'weekday' => $weekday,
                'colorWeekday' =>$colorWeekday,
            ];
            foreach($listTimesheet as $value){
                if($dateFilter[$i] == $value->date){
                       
                    $userListTimesheet[$i] = [
                        'id' => $value->id,
                        'date' => $dateFilter[$i], 
                        'weekday' => $weekday,
                        'colorWeekday' => $colorWeekday,
                        'first_checkin' => $value->first_checkin,
                        'last_checkout' => $value->last_checkout,
                        'working_hour' => $value->working_hour,
                        'overtime' => $value->overtime,
                        'leave_status' => $value->leave_status,
                        'status' => $value->status
                    ];            
                    break;
                }
            }
        }
        // dd($userListTimesheet);
        return view('frontend.dashboard', $this->data)->with('userListTimesheet',$userListTimesheet);
    }
    //Lấy thứ trong tuần
    public function getWeekday($date)
    {
        $millisecondWeekday = strtotime($date);
        $weekday = getdate($millisecondWeekday);
        switch ($weekday['weekday']) {
            case "Monday":
                return 'T2';
                break;
            case 'Tuesday':
                return 'T3';
                break;
            case 'Wednesday':
                return 'T4';
                break;
            case "Thursday":
                return 'T5';
                break;
            case 'Friday':
                return 'T6';
                break;
            case 'Saturday':
                return 'T7';
                break;
            case 'Sunday':
                return 'CN';
                break;
        }  
    }
    //Lấy màu các thứ
    public function getColorWeekday($date)
    {
        $millisecondWeekday = strtotime($date);
        $weekday = getdate($millisecondWeekday);
        // $today = date('l');

        if($date == date('d-m-Y'))
            return '#e9e951';
        else if($weekday['weekday'] == 'Saturday')
            return '#beecbe';    
        else if($weekday['weekday'] == 'Sunday')
            return '#f5cbcb';       

        return '';
    }

    //Lấy ra tất cả các ngày trong tuần
    public function getWeekdayNow($date)
    {
        $now = $date;
        $week_day = date("w", $now)-1;
        $week_date = date("d-m-Y", strtotime("-$week_day day", $now));

        // Tạo mảng các ngày của tuần hiện tại
        $days = array();
        for ($i = 0; $i < 7; $i++) {
            $days[] = date("d-m-Y", strtotime("+$i day", strtotime($week_date)));
        }
        return $days;
    }

    //Lấy các ngày làm việc trong tháng (-T7,CN)
    public function countWeekdaysInMonth($month, $year) {
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $countWeekdays = 0;
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $weekday = date('N', strtotime("$year-$month-$day"));
            if ($weekday < 6) {
                $countWeekdays++;
            }
        }
        return $countWeekdays;
    }
    
}

