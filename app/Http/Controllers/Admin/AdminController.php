<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Timesheet;


class AdminController extends Controller
{
    public $data = [];
    public function index()
    {
        $this->data['userDetail'] = Auth::user();
        $this->data['timesheetList'] = Timesheet::orderBy('created_at','desc')->get();
        return view('backend.dashboard', $this->data);
    }
}
