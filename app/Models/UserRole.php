<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $table = 'user_role';
    use HasFactory;

    //relationship
    public function user(){
        return $this->hasMany(User::class);
    }

    protected $fillable = [
        'id',
        'role_name',
    ];
    
    
}
