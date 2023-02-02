<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Timesheet;
use App\Models\Staffs;
use App\Models\HistoryInout;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{

    public $data = [];
    public function __construct(){
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }
    public function index(Request $request)
    {
        $this->data['userDetail'] = Auth::user();
        $this->data['staffsList'] = Staffs::all();   
        $filter = [];
        // lọc dữ liệu 
        if(!empty($request->staff_id)){
            $filter[] = ['staff_id','=',$request->staff_id];
        }
        if(!empty($request->date_filter)){
            $dateFilter = $request->date_filter;
        }
        else{
            $dateFilter = date('Y-m-d');
        }
        $timesheetList = Timesheet::where($filter)->orderBy('date','desc')->get();   
        foreach($timesheetList as $key=>$item){
            if(date('Y-m-d',$item->date/1000) == $dateFilter){
                $this->data['timesheetsList'][$key] = [
                    'id' => $item->id,
                    'full_name' => Staffs::where('id', $item->staff_id)->pluck('full_name')->first(),
                    'staff_id' => $item->staff_id,
                    'date' => $item->date,
                    'first_checkin' => $item->first_checkin,
                    'last_checkout' => $item->last_checkout,
                    'working_hour' => $item->working_hour,
                    'overtime' => $item->overtime,
                    'status' => $item->status,
                    'leave_status' => $item->leave_status,
                ];
            }
        }
        // dd($this->data['timesheetsList']);
        return view('backend.dashboard', $this->data);  
    }   
}
