@extends('frontend.layouts.master')
@section('title','Forget')

@section('content')
<div class="main-panel">
<div class="content-wrapper">
<div class="row">
<div class="col-md-12 grid-margin stretch-card">
<div class="card">
<div class="card-body">
    <h4 class="card-title">Forget</h4>

    {{-- Thông báo lỗi tổng quát--}}
    @if ($errors->any())
    <div class="alert alert-danger">Dữ liệu nhập không hợp lệ!</div>
    @endif

    <form class="forms-sample" action="{{route('option-post-forget',$timesheetDetail->id)}}" method="post">
    @csrf  
        {{-- info --}}
        <div class="form-group row">
            
            <div class="col-sm-9">
                <p>Enter your data!</p>
                <p>Date: {{date('d-m-Y',$timesheetDetail->date/1000)}}</p>
            </div>
        </div>

        {{-- last checkout --}}
        <div class="form-group row">
            <label for="exampleInputEmail2" class="col-sm-3 col-form-label">Last Checkout</label>
            <div class="col-sm-9">
            <input type="time" class="form-control" id="exampleInputEmail2" 
            name="last_checkout" value="{{old('last_checkout')}}">

            {{-- Thông báo lỗi --}}
            @error('last_checkout')
            <span style="color: red">{{$message}}</span>
            @enderror

            </div>
        </div>

        {{-- reason --}}
        <div class="form-group row">
            <label for="exampleInputEmail2" class="col-sm-3 col-form-label">Reason</label>
            <div class="col-sm-9">
                <textarea name="reason" class="form-control" id="exampleTextarea1" rows="4"></textarea>             
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary mr-2" style="margin-right: 10px">Submit</button>
        <a href="{{route('client-dashboard')}}" class="btn btn-secondary">Cancel</a>
    </form>
</div>   
</div>   
</div>   
</div>   
</div>   
</div>   
@endsection


