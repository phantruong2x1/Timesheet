<?php

namespace App\Models;

use App\Http\Controllers\Admin\TimesheetController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Timesheet extends Model
{
    use HasFactory;

    protected $table = 'timesheets';

    protected $fillable = [
        'staff_id',
        'date',
        'first_checkin',
        'last_checkout',
        'working_hour',
        'overtime',
        'leave_status'
    ];

    //relationship 
    public function staff(){
        return $this->belongsTo(Staffs::class);
    }
    public function requestDetail(){
        return $this->hasMany(RequestDetail::class);
    }

    public function createTimesheet($data)
    {
        $timesheetCon = new TimesheetController();
        $timesheet = new Timesheet;
        $timesheet->staff_id = $data['staff_id'];
        $timesheet->date = date('d-m-Y',strtotime($data['date']));
        $timesheet->first_checkin = strtotime($data['first_checkin'])*1000;
        $timesheet->last_checkout = strtotime($data['last_checkout'])*1000;
        $timesheet->working_hour = $timesheetCon->getWorkingHour($timesheet->first_checkin, $timesheet->last_checkout);
        $timesheet->overtime = $timesheetCon->getOverTime($timesheet->working_hour);
        $staffDetail = Staffs::findOrFail($data['staff_id']);
        if($staffDetail->shift == 'Ca 2')
            $timesheet->status = $timesheetCon->getStatusShift2($timesheet->first_checkin, $timesheet->last_checkout);
        else
            $timesheet->status = $timesheetCon->getStatusShift1($timesheet->first_checkin, $timesheet->last_checkout);
        $timesheet->leave_status = $data['leave_status'];

        $status = $timesheet->save();
        return $status;
    }

    public function updateTimesheet($id,$data)
    {
        $timesheetCon = new TimesheetController();
        $timesheet = Timesheet::findOrFail($id);
        $timesheet->staff_id = $data['staff_id'];
        $timesheet->date = date('d-m-Y',strtotime($data['date']));
        $timesheet->first_checkin = strtotime($data['first_checkin'])*1000;
        $timesheet->last_checkout = strtotime($data['last_checkout'])*1000;
        $timesheet->working_hour = $timesheetCon->getWorkingHour($timesheet->first_checkin, $timesheet->last_checkout);
        $timesheet->overtime = $timesheetCon->getOverTime($timesheet->working_hour);
        $staffDetail = Staffs::findOrFail($data['staff_id']);
        if($staffDetail->shift == 'Ca 2')
            $timesheet->status = $timesheetCon->getStatusShift2($timesheet->first_checkin, $timesheet->last_checkout);
        else
            $timesheet->status = $timesheetCon->getStatusShift1($timesheet->first_checkin, $timesheet->last_checkout);
        $timesheet->leave_status = $data['leave_status'];

        $status = $timesheet->save();
        return $status;
    }
}
