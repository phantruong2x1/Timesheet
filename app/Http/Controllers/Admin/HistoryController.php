<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HistoryInout;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    public $data = [];
    
    public function __construct(){
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }
    public function index()
    {    
        $this->data['title'] = 'History';
        $this->data['historyList'] = HistoryInout::orderBy('time', 'desc')->paginate(15);
        
        return view('backend.historis.list-history', $this->data);
    }

}
