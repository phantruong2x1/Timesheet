<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Timesheet;
use App\Models\RequestDetail;
use App\Models\Statisticals;
use App\Models\Staffs;
use App\Http\Controllers\Admin\TimesheetController;
use Illuminate\Support\Facades\Session;

class ClientController extends Controller
{
    public $data = [];
    
    public function __construct(){
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }

    public function index(Request $request)
    {
        // Lấy số ngày làm trong tháng (-T7, CN)
        $this->data['countDaysWork'] = $this->countWeekdaysInMonth(date('m'), date('Y'));
        // Lấy số thiệu thống kê của tháng hiện tại
        $this->data['statisticalDeatil'] = Statisticals::where('staff_id', Auth::user()->staff_id)->where('time',date('m-Y'))->first();
        // Thông tin tài khoản
        $this->data['userDetail'] = Auth::user();
        // Thông tin bảng chấm công của tài khoản
        $this->data['userTimesheet'] =  Timesheet::where('staff_id',Auth::user()->staff_id)->orderBy('date', 'DESC')->first();

        $userListTimesheet[] = [];
        //Lấy các ngày của tuần hiện tại
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
    public function getStaffInfo()
    {
        $staffDetail = Staffs::findOrFail(Auth::user()->staff_id);
        dd($staffDetail);
        return view('frontend.layouts.partials.navbar')->with('staffDetail1',$staffDetail);
    }

     // check in and out
     public function checkInAndOut(){
        $date = time()*1000;
        $status = $this->createAndUpdateTimesheet(Auth::user()->staff_id, $date, null);

        // Hiển thị câu thông báo 1 lần (Flash session)
        if($status == 'Check-In')
            Session::flash('toast-success', 'Cập Nhập Thời Gian Đến Thành Công!');
        else if($status == 'Check-Out')
            Session::flash('toast-success', 'Cập Nhập Thời Gian Về Thành Công!');
        else
            Session::flash('toast-error', 'Đã có lỗi xảy ra! Xin vui lòng kiểm tra lại.');

        return redirect()->back();
    }

    // Tạo và cập nhập thời gian biểu.
    public function createAndUpdateTimesheet($staff_id, $date, $recordId){
        $timesheetCon = new TimesheetController();

        //Lấy bản ghi mới nhất theo staff_id
        $timesheetDetail = Timesheet::where('staff_id',$staff_id)->where('date',date('d-m-Y'))->first();
        $staffDetail = Staffs::find($staff_id);
        //Nếu Staff_id = null thì tạo mới + tạo bản ghi mới theo ngày
        if(!$timesheetDetail){
            $timeSheets = new Timesheet();
            $timeSheets->record_id      =   $recordId;
            $timeSheets->date           =   date('d-m-Y',$date/1000);
            $timeSheets->first_checkin  =   $date;
            $timeSheets->staff_id       =   $staff_id;
            if(empty($staffDetail) || $staffDetail->shift == 'Ca 1')
            {
                if(date("H:i:s",$date/1000) > '08:30:00')
                    $timeSheets->status   = 'Late checkin';
            }
            else if($staffDetail->shift == 'Ca 2')
            {
                if(date("H:i:s",$date/1000) > '08:00:00')
                    $timeSheets->status   = 'Late checkin';
            } 
            $timeSheets->save();
            return 'Check-In';
        }
        //Update data for 2nd checkin
        else{ 
            $timesheetDetail->last_checkout  = $date;
            $timesheetDetail->working_hour   = $timesheetCon->getWorkingHour($timesheetDetail->first_checkin, $timesheetDetail->last_checkout);
            $timesheetDetail->overtime = $timesheetCon->getOverTime($timesheetDetail->working_hour);
            //Kiểm tra status có giấy phép hay không
            if(!strpos($timesheetDetail->status, 'Authorization')){
                if(empty($staffDetail->shift) || $staffDetail->shift == 'Ca 1')
                    $timesheetDetail->status = $timesheetCon->getStatusShift1($timesheetDetail->first_checkin, $timesheetDetail->last_checkout);
                else if($staffDetail->shift == 'Ca 2')
                    $timesheetDetail->status = $timesheetCon->getStatusShift2($timesheetDetail->first_checkin, $timesheetDetail->last_checkout);
            
            }
           
            $timesheetDetail->save();
            return 'Check-Out';
        }
        
    }
}

