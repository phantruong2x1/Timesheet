<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';

    public function staff(){
        return $this->hasMany(Staffs::class);
    }

    // Quy ước đặt tên table
    // Tên Model: Post 		=> table: posts
    // Tên Model: ProductCategory 	=> table: product_categories

    //Quy ước khóa chính, mặc định laravel lấy id làm khóa chính
    protected $primaryKey = 'id';
    //public $incrementing = flase;
    //protected $keyType = 'string';

    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'department_name',
        'department_desc',
    ];

    protected $attributes = [
        'department_desc' => 'none'
    ];

}
