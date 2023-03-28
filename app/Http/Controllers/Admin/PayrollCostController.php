<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PayrollCost;
use Illuminate\Support\Facades\Session;

class PayrollCostController extends Controller
{

    public function index()
    {
        $payrollCostsList = PayrollCost::orderBy('updated_at','desc')->get();

        return view('backend.payroll-costs.list-payroll-cost')->with( 'payrollCostsList',$payrollCostsList);
    }

    public function create()
    {
        # code...
    }
    public function store(Request $request)
    {
        
    }
    public function show()
    {
        
    }

    // đổ ra giao diện chỉnh sửa
    public function edit(Request $request,$id)
    {
        $payrollCostDetail = PayrollCost::findOrFail($id);
        $request->session()->put('id',$id);
        return response()->json($payrollCostDetail);
    }
    // Update dữ liệu
    public function update(Request $request)
    {
        $id = session('id');
        $request->validate([
            'cost' => 'required'
        ]);
        $payrollCostDetail = PayrollCost::findOrFail($id);
        $payrollCostDetail->cost = $request->cost;
        $status = $payrollCostDetail->save();

        if($status){
            Session::flash('alert-info', 'Sửa thành công!');
        }
        else {
            Session::flash('alert-danger', 'Đã có lỗi xảy ra!');
        }
        return redirect()->route('payroll-costs.index');
    }

    public function destroy()
    {

    }
}
