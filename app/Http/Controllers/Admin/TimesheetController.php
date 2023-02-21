<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Timesheet;
use App\Models\HistoryInout;
use App\Models\Staffs;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class TimesheetController extends Controller
{
    public $data = [];
    public function __construct()
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    public function getDataTimesheet()
    {  
        $this->data['title'] = 'Get data timesheet';
        return view('backend.others.get-data-timesheet',$this->data);
    }

    public function postDataTimesheet(Request $request)
    {
        $request->validate([
            'start_date' => 'required',
        ]);

        $millisecond = strtotime('1-'.$request->start_date.'');
        $monthFilter = date('m',$millisecond);
        $yearFilter = date('Y',$millisecond);
        $dayOfMonth = cal_days_in_month(CAL_GREGORIAN, $monthFilter, $yearFilter);
        $startDate = mktime(0, 0, 0, $monthFilter, 1, $yearFilter)*1000;
        $endDate = mktime(23, 59, 59, $monthFilter, $dayOfMonth, $yearFilter)*1000;
        $curl = curl_init();
        //điều kiện lấy dữ liệu
        $dateNow        = time()*1000;
        $clientId       = '431201014e0544ebb8122bdaa68fd534';
        $accessToken    = '3429d1f497d1d793083304f054bb0472';
        $lockId         = '4910283';
        $pageNo         = '1';
        $pageSize       = '10000';
        $startDate      = $startDate;  
        $endDate        = $endDate;
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
                    $timesheetDetail = Timesheet::where('staff_id',$list->list[$i]->username)->orderBy('first_checkin', 'DESC')->first();
                    $staffDetail = Staffs::find($list->list[$i]->username);
                    //Nếu Staff_id = null thì tạo mới
                    if(empty($timesheetDetail)){
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
                    //Tạo mới bản ghi theo ngày
                    else if($timesheetDetail->date != date('d-m-Y',($list->list[$i]->lockDate)/1000)){
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
                        $timesheetDetail->working_hour   = $this->getWorkingHour($timesheetDetail->first_checkin, $timesheetDetail->last_checkout);
                        $timesheetDetail->overtime = $this->getOverTime($timesheetDetail->working_hour);
                        if(empty($staffDetail->shift))
                            $timesheetDetail->status = $this->getStatusShift1($timesheetDetail->first_checkin, $timesheetDetail->last_checkout);
                        else if($staffDetail->shift == 'Ca 2')
                            $timesheetDetail->status = $this->getStatusShift2($timesheetDetail->first_checkin, $timesheetDetail->last_checkout);
                        else
                            $timesheetDetail->status = $this->getStatusShift1($timesheetDetail->first_checkin, $timesheetDetail->last_checkout);

                        $timesheetDetail->save();
                    }
                }          
            }         
        // Hiển thị câu thông báo 1 lần (Flash session)
        Session::flash('alert-info', 'Insert data successfully!');
        return redirect()->route('timesheets.get-time');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->data['title'] = 'Add timesheet';
        $this->data['staffsList'] = Staffs::all();
        return view('backend.timesheets.add-timesheet',$this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'staff_id' => 'required',
            'date'=>'required',
            'first_checkin' => 'required',
            'last_checkout' => 'required',
        ]);
        
        $data = $request->all();
        $timesheet = new Timesheet;
        $status = $timesheet->createTimesheet($data);
        if($status){
            Session::flash('alert-info', 'Thêm thành công ^^~!!!');
        }
        else {
            Session::flash('alert-danger', 'Đã có lỗi xảy ra!');
        }

        return redirect()->route('admin-dashboard');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $clientCon = new ClientController();
        $this->data['userDetail'] = Auth::user();
        $this->data['staffsList'] = Staffs::all(); 
        $filter = [];
        //lọc dữ liệu
        if(!empty($request->staff_id))
            $filter[] = ['staff_id','=',$request->staff_id];
        else
            $filter[] = ['staff_id','=', Auth::user()->staff_id];

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
        $listTimesheet = Timesheet::where($filter)->orderBy('date','desc')->get();

        for($i=1; $i<=$dayOfMonth; $i++){
            $dateFilter = date('d-m-Y',mktime(0, 0, 0, $monthFilter, $i, $yearFilter));
            $millisecondWeekday = strtotime($dateFilter);
            $weekday = getdate($millisecondWeekday);
            $colorWeekday = $clientCon->getColorWeekday($weekday);
            if($dateFilter == date('d-m-Y'))
                $colorWeekday = '#FFFF66';
                
            $this->data['userListTimesheet'][$i] = [
                'date' => $dateFilter, 
                'weekday' => $clientCon->getWeekday($weekday['weekday']),
                'colorWeekday' => $colorWeekday
            ]; 
            foreach($listTimesheet as $timesheetDetail){
                if($dateFilter == $timesheetDetail->date){
                   
                    $this->data['userListTimesheet'][$i] = [
                        'id' => $timesheetDetail->id,
                        'date' => $dateFilter, 
                        'weekday' => $clientCon->getWeekday($weekday['weekday']),
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
        return view('backend.timesheets.show-timesheet',$this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->data['title'] = 'Edit timesheet';
        $this->data['staffsList'] = Staffs::all();
        $this->data['timesheetDetail'] = Timesheet::find($id);
        // dd(date('d/m/Y',$this->data['timesheetDetail']->date/1000));
        return view('backend.timesheets.edit-timesheet',$this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'staff_id' => 'required',
            'date'=>'required',
            'first_checkin' => 'required',
            'last_checkout' => 'required',
        ]);

        $data = $request->all();
        $timesheet = new Timesheet;
        $status = $timesheet->updateTimesheet($id,$data);

        if($status){
            Session::flash('alert-info', 'Cập nhập thành công ^^~!!!');
        }
        else {
            Session::flash('alert-danger', 'Đã có lỗi xảy ra!');
        }

        return redirect()->route('admin-dashboard');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $timesheetDetail= Timesheet::findOrFail($id);
        $timesheetDetail->delete();
        Session::flash('alert-info', 'Xóa thành công!');
        return redirect()->route('admin-dashboard');
    }

    //Lấy thời gian làm việc
    public function getWorkingHour($first_checkin,$last_checkout)
    {
        if(date('H:i:s',$last_checkout/1000) >= '12:00:00')
            return ($last_checkout -$first_checkin)-(60*60*1000);
        else
            return ($last_checkout -$first_checkin); 
    }
    //Lấy thời gian tăng ca
    public function getOverTime($working_hour)
    {
        if($working_hour > (8*60*60*1000)){
            return $working_hour - (8*60*60*1000);
        }
        else{
            return 0;
        }
    }
    //Lấy trạng thái làm việc 
    public function getStatusShift1($first_checkin, $last_checkout)
    {
        
        if(date('H:i:s',$first_checkin/1000) > '08:30:00')
            $status = 'Late checkin';
        if( date('H:i:s',$first_checkin/1000) > '08:30:00' && 
        date('H:i:s',$last_checkout/1000) <= '17:30:00' ){
            $status = 'Late checkin/Early checkout';
        }
        //check early checkout
        else if(date('H:i:s',$last_checkout/1000) < '17:30:00'){
            $status = 'Early checkout';
        }
        else if(date('H:i:s',$first_checkin/1000) <= '08:30:00'){
            $status = 'On Time';
        } 
        return $status;
    }
    public function getStatusShift2($first_checkin, $last_checkout)
    {
        if(date('H:i:s',$first_checkin/1000) > '08:00:00')
            $status = 'Late checkin';

        if( date('H:i:s',$first_checkin/1000) > '08:00:00' && 
        date('H:i:s',$last_checkout/1000) <= '17:00:00' ){
            $status = 'Late checkin/Early checkout';
        }
        //check early checkout
        else if(date('H:i:s',$last_checkout/1000) < '17:00:00'){
            $status = 'Early checkout';
        }
        else if(date('H:i:s',$first_checkin/1000) <= '08:00:00'){
            $status = 'On Time';
        } 
        return $status;
    }
}
