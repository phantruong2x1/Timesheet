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
            $dateFilter = date('d-m-Y', strtotime($request->date_filter));
            $filter[] = ['date','=',$dateFilter];
        }
        else{
            $dateFilter = date('d-m-Y');
            $filter[] = ['date','=',$dateFilter];
        }
        $this->data['timesheetList'] = Timesheet::where($filter)->orderBy('first_checkin','desc')->get();  
        return view('backend.dashboard', $this->data);  
    }   
}

