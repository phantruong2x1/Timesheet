<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Timesheet;

class ClientController extends Controller
{
    public $data = [];
    
    public function __construct(){
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }

    public function index()
    {
        $this->data['userDetail'] = Auth::user();
        //Lấy 1 dữ liệu timesheet mới nhất của Auth::user
        $this->data['userTimesheet'] =  Timesheet::where('staff_id',Auth::user()->staff_id)
                                        ->orderBy('date', 'DESC')
                                        ->first();

        //Lấy toàn bộ dữ liệu timesheet của Auth::user
        $this->data['userListTimesheet'] =  Timesheet::where('staff_id',Auth::user()->staff_id)
                                        ->orderBy('date','desc')->take(10)->get();
        $this->data['dt'] = date('d-m-Y');
        return view('frontend.dashboard', $this->data);
    }
}

