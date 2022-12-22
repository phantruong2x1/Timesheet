<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Timesheet;
use App\Models\HistoryInout;
use Illuminate\Support\Facades\DB;


class AdminController extends Controller
{

    public $data = [];
    public function __construct(){
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }
    public function index()
    {
        $this->data['userDetail'] = Auth::user();
        $this->data['timesheetsList'] = Timesheet::orderBy('date','desc')->simplePaginate(14);

        $curl = curl_init();

        //điều kiện lấy dữ liệu
        $dateNow        = time()*1000;
        $clientId       = '431201014e0544ebb8122bdaa68fd534';
        $accessToken    = '3429d1f497d1d793083304f054bb0472';
        $lockId         = '4910283';
        $pageNo         = '1';
        $pageSize       = '10';
        $startDate      = '0';
        $endDate        = '0';

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
        // var_dump($list);
        curl_close($curl);
        
        //add timesheet
        foreach ($list->list as  $item) {

            //Kiểm tra trùng recordId
            $checkHistoryList = DB::table('history_inouts')->pluck('record_id')->toArray();
            if(!in_array($item->recordId, $checkHistoryList )){

                //Insert data in table history_inouts
                $historyInouts = new HistoryInout();
                $historyInouts->record_id   = $item->recordId;
                $historyInouts->time        = $item->lockDate;
                $historyInouts->staff_id    = $item->username;
                $historyInouts->record_type = $item->recordType;
                $historyInouts->save();
                
                if($item->recordType == 8){
                    $checkTimeList = DB::table('timesheets')->pluck('staff_id')->toArray();
                    
                    if(!in_array($item->username, $checkTimeList) && 
                    (date("d-m-Y", $item->lockDate/1000))==(date("d-m-Y")) ) {

                        //Insert data in table timesheets
                        $timeSheets = new Timesheet();
                        $timeSheets->record_id      = $item->recordId;
                        $timeSheets->date           = $item->lockDate;
                        $timeSheets->first_checkin  = $item->lockDate;
                        $timeSheets->staff_id       = $item->username;
                        $timeSheets->save();
                    }
                    else if(in_array($item->username, $checkTimeList) &&   
                    (date("d-m-Y", $item->lockDate/1000))==(date("d-m-Y")) ){
                        $timeSheet1 = Timesheet::where('staff_id',$item->username)->first();

                        $timeSheet1->last_checkout  = $item->lockDate;
                        $timeSheet1->working_hour   =  $timeSheet1->last_checkout -$timeSheet1->first_checkin;
                        if($timeSheet1->working_hour > (8*60*60*1000)){
                            $timeSheet1->overtime       = $timeSheet1->working_hour - (8*60*60*1000);
                            $timeSheet1->leave_status   = 'OK';
                        }
                        else{
                            $timeSheet1->overtime = 0;
                        }
                        
                        $timeSheet1->save();
                    }
                    
                }
            }
            
        }
        

        return view('backend.dashboard', $this->data);
    }
    
}
