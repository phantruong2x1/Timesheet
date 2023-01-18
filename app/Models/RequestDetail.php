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
        return $this->hasMany(Timesheet::class);
    }
}
