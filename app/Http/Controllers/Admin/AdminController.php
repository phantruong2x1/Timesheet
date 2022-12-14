<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AdminController extends Controller
{
    public $data = [];
    public function index()
    {
        $this->data['userDetail'] = Auth::user();
        return view('backend.dashboard', $this->data);
    }
}
