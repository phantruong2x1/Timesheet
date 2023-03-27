<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\HistoryInout;
use App\Models\Timesheet;
use App\Models\Staffs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Nette\Utils\Arrays;
use App\Http\Controllers\Admin\TimesheetController;

class GetCurl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'GetCurl:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get data curl ttlock';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function __construct()
    {
        parent::__construct();
    }
    public function handle(Request $request)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $curl = curl_init();
        $timesheet = new TimesheetController();

        //điều kiện lấy dữ liệu
        $dateNow        = time()*1000;
        $clientId       = '431201014e0544ebb8122bdaa68fd534';
        $accessToken    = '3429d1f497d1d793083304f054bb0472';
        $lockId         = '4910283';
        $pageNo         = '1';   
        $pageSize       = '200';
        $startDate      = (time()-901)*1000;     
        $endDate        = '';
        //(time()-3600)*1000

        curl_setopt_array($curl, array(
        CURLOPT_URL             => 'https://api.sciener.com/v3/lockRecord/list?clientId='.$clientId.
        '&accessToken='.$accessToken.'&lockId='.$lockId.'&pageNo='.$pageNo.'&pageSize='.$pageSize.
        '&date='.$dateNow.'&startDate='.$startDate.'&endDate='.$endDate.'',
        CURLOPT_RETURNTRANSFER  => true,
        CURLOPT_ENCODING        => '',
        CURLOPT_MAXREDIRS       => 10,
        CURLOPT_TIMEOUT         => 0,
        CURLOPT_FOLLOWLOCATION  => true,
        CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST   => 'GET',
        ));
        $response = curl_exec($curl);

        //return json
        $list = json_decode($response);
        //var_dump($list);
        curl_close($curl);
        
        //add timesheet
        for ($i = count($list->list) - 1; $i >= 0; $i--) {

            //Kiểm tra trùng recordId
            $checkHistoryList = DB::table('history_inouts')->orderBy('time','DESC')->take(10)->pluck('record_id')->toArray();
            if(!in_array($list->list[$i]->recordId, $checkHistoryList )){

                //Insert data in table history_inouts
                $historyInouts = new HistoryInout();
                $historyInouts->record_id   = $list->list[$i]->recordId;
                $historyInouts->time        = $list->list[$i]->lockDate;
                $historyInouts->staff_id    = $list->list[$i]->username;
                $historyInouts->record_type = $list->list[$i]->recordType;
                $historyInouts->save();
                
                //Ktra recordType = 8
                if($list->list[$i]->recordType == 8){

                    //Lấy bản ghi mới nhất theo staff_id
                    $timesheetDetail = Timesheet::where('staff_id',$list->list[$i]->username)->where('date',date('d-m-Y',($list->list[$i]->lockDate)/1000))->first();
                    $staffDetail = Staffs::find($list->list[$i]->username);
                    //Nếu Staff_id = null thì tạo mới + tạo bản ghi mới theo ngày
                    if($timesheetDetail->isEmpty()){
                        $timeSheets = new Timesheet();
                        $timeSheets->record_id      = $list->list[$i]->recordId;
                        $timeSheets->date           = date('d-m-Y',$list->list[$i]->lockDate/1000);
                        $timeSheets->first_checkin  = $list->list[$i]->lockDate;
                        $timeSheets->staff_id       = $list->list[$i]->username;
                        if(empty($staffDetail) || $staffDetail->shift == 'Ca 1')
                        {
                            if(date("H:i:s",$list->list[$i]->lockDate/1000) > '08:30:00')
                                $timeSheets->status   = 'Late checkin';
                        }
                        else if($staffDetail->shift == 'Ca 2')
                        {
                            if(date("H:i:s",$list->list[$i]->lockDate/1000) > '08:00:00')
                                $timeSheets->status   = 'Late checkin';
                        } 
                        $timeSheets->save();
                    }
                    //Update data for 2nd checkin
                    else{ 
                        $timesheetDetail->last_checkout  = $list->list[$i]->lockDate;
                        $timesheetDetail->working_hour   = $timesheet->getWorkingHour($timesheetDetail->first_checkin, $timesheetDetail->last_checkout);
                        $timesheetDetail->overtime = $timesheet->getOverTime($timesheetDetail->working_hour);
                        //Kiểm tra status có giấy phép hay không
                        if(strpos($timesheetDetail->status, 'Authorization'))
                            continue;
                        else if(empty($staffDetail->shift) || $staffDetail->shift == 'Ca 1')
                            $timesheetDetail->status = $timesheet->getStatusShift1($timesheetDetail->first_checkin, $timesheetDetail->last_checkout);
                        else if($staffDetail->shift == 'Ca 2')
                            $timesheetDetail->status = $timesheet->getStatusShift2($timesheetDetail->first_checkin, $timesheetDetail->last_checkout);
                            
                        $timesheetDetail->save();
                    }
                }
            }   
        }


    }
}
