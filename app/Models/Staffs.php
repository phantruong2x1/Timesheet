<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Staffs extends Model
{
    use HasFactory;

    protected $table = 'staff';

    protected $primaryKey = 'id';
    //public $incrementing = flase;
    protected $keyType = 'string';

    protected $fillable = [
            'position_id',
            'department_id',
            'full_name',
            'birthday',
            'gender',
            'email',
            'shift',
            'type',
            'user_id',
            'id',
            'phone_number',
            'address'
    ];
    protected $hidden = [
        'tax_code',
        'email_company'
    ];
        
    //relationship
    public function position(){
        return $this->belongsTo(Position::class);
    }
    public function department(){
        return $this->belongsTo(Department::class);
    }
    public function user(){
        return $this->hasOne(User::class);
    }
    public function timesheet(){
        return $this->hasMany(Timesheet::class);
    }
    public function feekback(){
        return $this->hasMany(Feekback::class);
    }
    public function requestDetail(){
        return $this->hasMany(RequestDetail::class);
    }

    // Lấy tất cả nhân viên 
    // public function getAllStaffs()
    // {
    //     return $staffs = DB::table($this->table)
    //     ->join('departments', 'staff.department_id', '=', 'departments.id' )
    //     ->join('positions', 'staff.position_id', '=', 'positions.id' )
    //     ->orderBy(''.$this->table.'.created_at','DESC')
    //     ->get();
    // }

    //Thêm mọt nhân viên
    public function addStaff($data)
    {
        DB::insert('INSERT INTO staff (id, full_name, birthday, gender, tax_code,
         phone_number, email, address,email_company, begin_time, end_time, official_time,
         type, department_id, position_id, shift, created_at) value (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', $data);
    }

    //Lấy thông tin 1 nhân viên
    public function getDetail($id)
    {
        return DB::select('SELECT * FROM '.$this->table.' WHERE id = ?', [$id]);
    }

    //update thông tin nhân viên
    public function updateStaff($data, $id)
    {
        $data[] = $id;

        return DB::update('UPDATE '.$this->table.'
        SET id=?, full_name=?, birthday=?, gender=?, tax_code=?,
        phone_number=?, email=?, address=?,email_company=?, begin_time=?, end_time=?, official_time=?,
        type=?, department_id=?, position_id=?, shift=?, updated_at =? WHERE id=?', $data);
    }

    //xóa thông tin nhân viên
    public function deleteStaff($id)
    {
        DB::delete("DELETE FROM $this->table WHERE id=?",[$id]);
    }

    
}
