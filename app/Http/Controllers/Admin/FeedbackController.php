<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    public $data = [];
    public function index()
    {
        $this->data['title'] = 'Feedback';
        $this->data['listFeedback'] = Feedback::orderBy('created_at','desc')->paginate(15);
        return view('backend.feedbacks.list-feedback',$this->data);
    }
}
