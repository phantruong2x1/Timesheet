<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statisticals extends Model
{
    protected $table = 'statistics';
    use HasFactory;

    //relationship
    public function staff(){
        return $this->belongsTo(Staffs::class);
    }

    protected $fillable = ['time', 'staff_id', 'working_date', 'working_hour','overtime_hour', 'last_checkin', 'early_checkout','day_off','salary'];
    //tạo bản ghi mới
    public static function createStatistical($data)
    {
        $status = Statisticals::create($data);
        return $status;
    }
    //cập nhập cho bản ghi theo staff_id và time = m-Y
    public static function updateStatistical($data,$staff_id, $time){
        $statistical = Statisticals::where('time',$time)->where('staff_id', $staff_id)->first();
        if($statistical){
            $statistical->working_date = $data['working_date'];
            $statistical->working_hour = $data['working_hour'];
            $statistical->overtime_hour = $data['overtime_hour'];
            $statistical->last_checkin = $data['last_checkin'];
            $statistical->early_checkout = $data['early_checkout'];
            $statistical->salary = $data['salary'];
            $statistical->day_off = $data['day_off'];

            $statistical->save();
        }

    }
}
