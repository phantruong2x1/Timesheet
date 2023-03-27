<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\StatisticalController;
use Illuminate\Console\Command;
use App\Models\Statisticals;
use App\Models\Timesheet;
use App\Models\Staffs;
use NunoMaduro\Collision\Adapters\Phpunit\State;

class UpLoadStatistical extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'UpLoadStatistical:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload tabl statistical';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $statisticalCon = new StatisticalController();
        $dataTimesheet = Timesheet::where('date', date('d-m-Y'))->get();
        $dataStaff = Staffs::all();
        //nếu sang tháng mới thì tạo mới data statistical
        if(date('d') == '1'){
            foreach($dataStaff as $key=>$item){
                $dataCreate = [
                    'time' =>date('m-Y'),
                    'staff_id' => $item->id,
                    'working_date' => 0,
                    'working_hour' => 0.0,
                    'overtime_hour' => 0.0,
                    'last_checkin' => 0,
                    'early_checkout' => 0,
                    'day_off' => 0,
                    'salary' => 0.0
                ];
                Statisticals::createStatistical($dataCreate);
            }
        }
        //cập nhập bản ghi theo ngày
        else{
            $startDateFilter = date('d-m-Y',strtotime('1-'.date('m').'-'.date('Y')));
            $endDateFilter = date('t-m-Y H:i:s',strtotime('1-'.date('m').'-'.date('Y').' 23:59:59'));
            $startDateFilter = strtotime($startDateFilter)*1000;
            $endDateFilter = strtotime($endDateFilter)*1000;
            foreach($dataStaff as $key => $item){
                //Khởi tạo các biến
                $timeList           = Timesheet::where('staff_id', $item->id)->get();
                $countDays          = 0;
                $countWorkHour      = 0.0;
                $countOverHour      = 0.0;
                $countLastCheckin   = 0;
                $countEarlyCheckout = 0;
                $totalSalary        = 0.0;
                $dayOff             = 0;
    
                foreach($timeList as $time){
                    if($startDateFilter <= (strtotime($time->date)*1000) &&  (strtotime($time->date)*1000) <= $endDateFilter){
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
                $totalSalary = $statisticalCon->calculationOfSalary($item->id,$countWorkHour, $countLastCheckin,$countEarlyCheckout, 0);
                $dayOff = $statisticalCon->getWorkDayOfNow(date('m-Y')) - $countDays;
                //Gán các biến vào mảng totalList 
                $dataUpdate = [            
                    'working_date'      => $countDays,
                    'working_hour'      => $countWorkHour,
                    'overtime_hour'     => $countOverHour,
                    'last_checkin'      => $countLastCheckin,
                    'early_checkout'    => $countEarlyCheckout,
                    'salary'            => $totalSalary,
                    'day_off'           => $dayOff,
                ];
                $status = Statisticals::updateStatistical($dataUpdate,$item->id,date('m-Y'));
            }
        }
        return Command::SUCCESS;
    }
}
