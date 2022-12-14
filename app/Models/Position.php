<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Position extends Model
{
    use HasFactory;
    
    protected $table = 'positions';

    public function staff(){
        return $this->hasMany(Staffs::class);
    }

    protected $fillable = [
        'position_name',
        'position_desc'
    ];
}
