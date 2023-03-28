<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Admin\TimesheetController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Statisticals;
use App\Models\Timesheet;
use App\Models\PayrollCost;
use App\Models\Staffs;

class StatisticalController extends Controller
{
    public $data = [];
    public function index(Request $request)
    {

        $timesheetCon = new TimesheetController();
        $clientCon = new ClientController();
        //lấy tháng và năm cần lọc
        $monthAndYear = $timesheetCon->getMonthAndYear($request->date_filter);
        
        $this->data['monthFilter'] = ''. $monthAndYear['month'].'-'.$monthAndYear['year'].'';
        $this->data['payroll'] = $this->getStatisticalDetail(''. $monthAndYear['month'].'-'.$monthAndYear['year'].'');
        $dayOfMonth = cal_days_in_month(CAL_GREGORIAN, $monthAndYear['month'], $monthAndYear['year']);
        $listTimesheet = Timesheet::where('staff_id', Auth::user()->staff_id)->orderBy('date','desc')->get();
        $this->data['countDaysWork'] = $clientCon->countWeekdaysInMonth($monthAndYear['month'], $monthAndYear['year']);
        $this->data['statisticalDeatil'] = Statisticals::where('staff_id', Auth::user()->staff_id)->where('time',''.$monthAndYear['month'].'-'.$monthAndYear['year'].'')->first();
        
        for($i=1; $i<=$dayOfMonth; $i++){

            $dateFilter = date('d-m-Y',mktime(0, 0, 0, $monthAndYear['month'], $i, $monthAndYear['year']));
            $weekday = $clientCon->getWeekday($dateFilter);
            $colorWeekday = $clientCon->getColorWeekday($dateFilter);

            $this->data['userListTimesheet'][$i] = [
                'date' => $dateFilter, 
                'weekday' => $weekday,
                'colorWeekday' => $colorWeekday
            ]; 
            foreach($listTimesheet as $timesheetDetail){
                if($dateFilter == $timesheetDetail->date){
                   
                    $this->data['userListTimesheet'][$i] = [
                        'id' => $timesheetDetail->id,
                        'date' => $dateFilter, 
                        'weekday' => $weekday,
                        'colorWeekday' => $colorWeekday,
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
        // dd($this->data['payroll']);
        return view('frontend.statisticals.statistical',$this->data);
    }
    
    public function getStatisticalDetail($time)
    {
        $staff = Staffs::where('id',Auth::user()->staff_id)->first();
        $statistical = Statisticals::where('staff_id',Auth::user()->staff_id)->where('time',$time)->first();
        if($statistical){
            $payroll = array();
            $payroll[0] = [
                'name' => 'Working Hour',
                'value' => number_format($statistical->working_hour/3600000,1),
                'cost' => $staff->coefficients_salary,
                'total' => $staff->coefficients_salary*number_format($statistical->working_hour/3600000,1),
                'salary_type' => 'Penalty'
            ];
            $payrollCost = PayrollCost::all();

            foreach($payrollCost as $key=>$item){
                $array = $this->getValueByName($item->type_cost,$statistical);
                $payroll[$key+1] = [
                    'name' => $item->type_cost,
                    'value' => $array['value'],
                    'cost' => $item->cost,
                    'total' => $array['value']*$item->cost,
                    'salary_type' =>$array['salary_type']
                ];
            }
            return $payroll;
        }
        return false;
  
    }

    function getValueByName($name, $statistical)
    {
        $array = array();
        switch($name){
            case 'Last Checkin':
                $array = [
                    'value' => $statistical->last_checkin,
                    'salary_type' => 'Reward'
                ];
                return $array;
                break;
            case 'Early Checkout':
                $array = [
                    'value' => $statistical->early_checkout,
                    'salary_type' => 'Reward'
                ];
                return $array;
                break;
            case 'Unauthorized Absences':
                $array = [
                    'value' => $statistical->day_off,
                    'salary_type' => 'Reward'
                ];
                return $array;
                break;
            default:
                $array = [
                    'value' => 1,
                    'salary_type' => 'Penalty'
                ];
                return $array;
                break;
        }
    }
}
