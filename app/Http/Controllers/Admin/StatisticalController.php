<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Timesheet;
use App\Models\Staffs;

class StatisticalController extends Controller
{
    public $data = [];

    public function index(Request $request)
    {
        $this->data['title'] = 'Statistical';

        if(!empty($request->date))
            $dataInsert = $request->date;
        else
            $dataInsert = date('m-Y');

        //Thêm dữ liệu thống kê vào totalList 
        foreach(Staffs::all() as $key => $item){
            //Khởi tạo các biến
            $timeList           = Timesheet::where('staff_id', $item->id)->get();
            $countDays          = 0;
            $countWorkHour      = 0.0;
            $countOverHour      = 0.0;
            $countLastCheckin   = 0;
            $countEarlyCheckout = 0;

            foreach($timeList as $time){
                if(date('m-Y',$time->date/1000)==$dataInsert){
                    $countDays++;
                    $countWorkHour += $time->working_hour;
                    $countOverHour += $time->overtime;
                    
                    if($time->status == 'Late checkin' || $time->status == 'Late checkin/Early checkout'){
                        $countLastCheckin++;
                    }
                    if($time->status == 'Early checkout' || $time->status == 'Late checkin/Early checkout'){
                        $countEarlyCheckout++;
                    }
                }
            }
            //Gán các biến vào mảng totalList 
            $this->data['totalList'][$key] = [             
                'staff_id'              => $item->id,
                'full_name'             => $item->full_name,
                'total_work_days'       => $countDays,
                'total_work_hours'      => $countWorkHour,
                'total_over_hours'      => $countOverHour,
                'total_last_checkin'    => $countLastCheckin,
                'total_early_checkout'  => $countEarlyCheckout
            ];
        }
        return view('backend.statisticals.list-statistical',$this->data);
    }
}
