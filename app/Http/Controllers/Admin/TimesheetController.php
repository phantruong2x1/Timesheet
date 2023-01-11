<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TimesheetController extends Controller
{
    public $data = [];
    public function index()
    {
        # code...
    }

    public function getDataTimesheet()
    {
        $this->data['title'] = 'Get data timesheet';
        return view('backend.others.get-data-timesheet',$this->data);
    }
}
