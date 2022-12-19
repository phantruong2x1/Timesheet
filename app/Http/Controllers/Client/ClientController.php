<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ClientController extends Controller
{
    public $data = [];
    
    
    public function index()
    {
        $this->data['user'] = Auth::user();
        $this->data['dt'] = Carbon::now('Asia/Ho_Chi_Minh');
        return view('frontend.dashboard', $this->data);
    }
}

