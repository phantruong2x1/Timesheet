<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PayrollCost;
use Illuminate\Http\Request;
use App\Models\Timesheet;
use App\Models\Staffs;
use App\Models\Statisticals;

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
        if(empty($request->end_date_filter) || empty($request->start_date_filter)){
            if(empty($request->date))
                $this->data['listStatistical'] = Statisticals::where('time',date('m-Y'))->orderBy('staff_id','asc')->get();
            else
                $this->data['listStatistical'] = Statisticals::where('time',$request->date)->orderBy('staff_id','asc')->get();
        }
        else{
            $dateFilter = $this->dateFilter($request->date, $request->start_date_filter,$request->end_date_filter);
            $this->data['listStatistical'] = $this->getDataByDateFilter($dateFilter['start'], $dateFilter['end']);
        }
        return view('backend.statisticals.list-statistical',$this->data);
    }
    //Lấy ngày để lọc dữ liệu
    public function dateFilter($month, $startDate, $endDate)
    {
        $dateFilter = array();
        if(!empty($startDate) && !empty($endDate)){
            $startDateFilter = $startDate;
            $endDateFilter = $endDate.' 23:59:59';
        }
        else if(!empty($month)){
            $startDateFilter = '1-'.$month;
            $endDateFilter = date('t-m-Y H:i:s',strtotime('1-'.$month.' 23:59:59'));
        }
        else{
            $startDateFilter = date('d-m-Y',strtotime('1-'.date('m').'-'.date('Y')));
            $endDateFilter = date('t-m-Y H:i:s',strtotime('1-'.date('m').'-'.date('Y').' 23:59:59'));
        }
        $dateFilter['start'] = strtotime($startDateFilter)*1000;
        $dateFilter['end'] = strtotime($endDateFilter)*1000;

        return $dateFilter;
    }

    //Tính lương
    public function calculationOfSalary($staff_id, $workHour, $lastCheckin, $earlyCheckout, $dayOff)
    {
        $staff = Staffs::findOrFail($staff_id);
        $payLastChecin = PayrollCost::where('type_cost','Last Checkin')->first()->cost;
        $payEarlyCheckOut = PayrollCost::where('type_cost','Early Checkout')->first()->cost;
        $payDayOff = PayrollCost::where('type_cost','Unauthorized Absences')->first()->cost;
        $petrolSupport = PayrollCost::where('type_cost','Petrol Support')->first()->cost;
        $otherPenatly = PayrollCost::where('type_cost','Other Penalty')->first()->cost;

        $salary =(($staff->coefficients_salary)*($workHour/3600000))- ($lastCheckin*$payLastChecin)
        - ($payEarlyCheckOut*$earlyCheckout) - ($dayOff*$payDayOff)
        + $petrolSupport + $otherPenatly;
        
        return $salary;
    }

    //lọc dữ liệu theo ngày từ ngày $dateFilterStart đến ngày $dateFilterEnd
    public function getDataByDateFilter($dateFilterStart,$dateFilterEnd)
    {
        $listStatistical = array();
        foreach(Staffs::all() as $key => $item){
            //Khởi tạo các biến
            $timeList           = Timesheet::where('staff_id', $item->id)->get();
            $countDays          = 0;
            $countWorkHour      = 0.0;
            $countOverHour      = 0.0;
            $countLastCheckin   = 0;
            $totalSalary        = 0.0;
            $countEarlyCheckout = 0;

            foreach($timeList as $time){
                if($dateFilterStart <= (strtotime($time->date)*1000) &&  (strtotime($time->date)*1000) <= $dateFilterEnd){
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
            $totalSalary = $this->calculationOfSalary($item->id,$countWorkHour,$countLastCheckin,$countEarlyCheckout,0);
            //Gán các biến vào mảng totalList 
            $listStatistical[$key] = [             
                'staff_id'            => $item->id,
                'full_name'           => $item->full_name,
                'working_date'        => $countDays,
                'working_hour'        => $countWorkHour,
                'overtime_hour'       => $countOverHour,
                'last_checkin'        => $countLastCheckin,
                'salary'              => $totalSalary,
                'early_checkout'      => $countEarlyCheckout
            ];    
        }
        return $listStatistical;
    }

    public function getWorkDayOfNow($time)
    {
        // Lấy ra mảng gồm các giá trị 'working_date'
        $workingDateArr = Statisticals::where('time',$time)->pluck('working_date')->toArray();

        if($workingDateArr){
            // Đếm số lần xuất hiện của các giá trị 'working_date'
            $countArr = array_count_values($workingDateArr);
        
            // Tìm giá trị 'working_date' xuất hiện nhiều nhất và lớn nhất
            $maxCount = max($countArr);
            $maxWorkingDate = max($workingDateArr);
        
            // Lấy giá trị ưu tiên 'working_date' xuất hiện nhiều nhất, sau đó là giá trị lớn nhất
            $priorityWorkingDate = array_search($maxCount, $countArr) ?: $maxWorkingDate;
        
            return $priorityWorkingDate;
        }
        
        return 0;
    }

}
