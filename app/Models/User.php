<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use NunoMaduro\Collision\Adapters\Phpunit\State;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    //relationship 
    public function staff(){
        return $this->belongsTo(Staffs::class);
    }
    public function user_role(){
        return $this->belongsTo(UserRole::class,'role_id');
    }
    
    protected $fillable = [
        'user_name',
        'staff_id',
        'email',
        'password',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        //'password',
        'remember_token',
        'email_verified_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function createUser($data)
    {
        $user = new User;
        $user->role_id = $data['role_id'];
        if(empty($data['staff_id']))
            $user->staff_id = null;
        else 
            $user->staff_id = $data['staff_id'];
        $user->user_name = $data['user_name'];
        $user->password = Hash::make($data['password']);
        $user->status = $data['status'];

        $user->save();
        return $user;
    }
    
    public function updateUser($id,$data)
    {
        $user = User::findOrFail($id);

        $user->role_id = $data['role_id'];
        if(empty($data['staff_id']))
            $user->staff_id = null;
        else 
            $user->staff_id = $data['staff_id'];
        $user->user_name = $data['user_name'];
        $user->password = Hash::make($data['password']);
        $user->status = $data['status'];

        $user->save();
        return $user;
    }
}
