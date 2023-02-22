<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;
    protected $table = 'feedbacks';

    //relationship 
    public function staff(){
        return $this->belongsTo(Staffs::class);
    }
 
    protected $fillable =['staff_id','title','content'];
    public function createFeedback($data)
    {
        $feekback = new Feedback();
        $feekback->staff_id = $data['staff_id'];
        $feekback->title = $data['title'];
        $feekback->content = $data['content'];

        $status = $feekback->save();
        return $status;
    }
}
