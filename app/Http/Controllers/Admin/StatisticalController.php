<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Timesheet;
use App\Models\Staffs;

class StatisticalController extends Controller
{
    public $data = [];

    public function __construct(){
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }

    public function index(Request $request)
    {
        $this->data['title'] = 'Statistical';
        //lấy ngày bắt đầu lọc và kết thúc lọc
        if(!empty($request->start_date_filter) && !empty($request->end_date_filter)){
            $startDateFilter = $request->start_date_filter;
            $endDateFilter = $request->end_date_filter.' 23:59:59';
        }
        else if(!empty($request->date)){
            $startDateFilter = '1-'.$request->date;
            $endDateFilter = '31-'.$request->date.' 23:59:59';
        }
        else{
            $startDateFilter = date('d-m-Y',strtotime('1-'.date('m').'-'.date('Y')));
            $endDateFilter = date('t-m-Y H:i:s',strtotime('1-'.date('m').'-'.date('Y').' 23:59:59'));
        }
        $startDateFilter = strtotime($startDateFilter)*1000;
        $endDateFilter = strtotime($endDateFilter)*1000;

        //Thêm dữ liệu thống kê vào totalList 
        foreach(Staffs::all() as $key => $item){
            //Khởi tạo các biến
            $timeList           = Timesheet::where('staff_id', $item->id)->get();
            $countDays          = 0;
            $countWorkHour      = 0.0;
            $countOverHour      = 0.0;
            $countLastCheckin   = 0;
            $countMinuteLastCheckin = 0.0;
            $countEarlyCheckout = 0;

            foreach($timeList as $time){
                if($startDateFilter <= $time->date &&  $time->date <= $endDateFilter){
                    $countDays++;
                    $countWorkHour += $time->working_hour;
                    $countOverHour += $time->overtime;
                    
                    if($time->status == 'Late checkin' || $time->status == 'Late checkin/Early checkout'){
                        $time1 = strtotime(date('H:i:s',$time->first_checkin/1000))*1000;
                        //Lấy ca làm việc của nhân viên để so sánh
                        if($item->shift == 'Ca 1')
                            $time2 = strtotime('08:30:00')*1000;
                        else 
                            $time2 = strtotime('08:00:00')*1000;

                        $countMinuteLastCheckin += ($time1-$time2);
                        $countLastCheckin++;
                    }
                    if($time->status == 'Early checkout' || $time->status == 'Late checkin/Early checkout'){
                        $countEarlyCheckout++;
                    }
                }
            }
            //Gán các biến vào mảng totalList 
            $this->data['totalList'][$key] = [             
                'staff_id'                  => $item->id,
                'full_name'                 => $item->full_name,
                'total_work_days'           => $countDays,
                'total_work_hours'          => $countWorkHour,
                'total_over_hours'          => $countOverHour,
                'total_last_checkin'        => $countLastCheckin,
                'count_minute_last_checkin' => $countMinuteLastCheckin,
                'total_early_checkout'      => $countEarlyCheckout
            ];
        }
        return view('backend.statisticals.list-statistical',$this->data);
    }
}
