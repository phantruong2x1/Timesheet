<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestDetail extends Model
{
    protected $table = 'request_detail';
    use HasFactory;

    protected $fillable = [
        //
    ];

    //relationship
    public function timesheet()
    {
        return $this->belongsTo(Timesheet::class,'timesheet_id');
    }
    public function staff(){
        return $this->belongsTo(Staffs::class,'staff_id');
    }
}
