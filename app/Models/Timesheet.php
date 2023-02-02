<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
