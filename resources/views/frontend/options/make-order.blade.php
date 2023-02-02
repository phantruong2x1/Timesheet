@extends('frontend.layouts.master')
@section('title','Forget')

@section('content')
<div class="main-panel">
<div class="content-wrapper">
<div class="row">
{{-- update checkout --}}
<div class="col-md-6 grid-margin stretch-card" style="display: {{$display}}">
<div class="card">
<div class="card-body" >
    <h4 class="card-title">Update Checkout</h4>

    {{-- Thông báo lỗi tổng quát--}}
    @if ($errors->any())
    <div class="alert alert-danger">Dữ liệu nhập không hợp lệ!</div>
    @endif

    <form class="forms-sample" action="{{route('option-post-forget',$listMakeOrder->id)}}" method="post">
    @csrf  
        {{-- info --}}
        <div class="form-group row">
            
            <div class="col-sm-9">
                <p>Enter your data!</p>
                <p>Date: <b>{{date('d-m-Y',$listMakeOrder->date/1000)}}</b></p>
                <p>First Checkin: <input type="time" name="first_checkin" readonly value="{{date('H:i:s',$listMakeOrder->first_checkin/1000)}}" ></p>
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
    </form>
</div>  
</div>  
</div>
{{-- please be late  --}}
<div class="col-md-6 grid-margin stretch-card">
<div class="card">
<div class="card-body" >
    <h4 class="card-title">Be Late</h4>

    {{-- Thông báo lỗi tổng quát--}}
    @if ($errors->any())
    <div class="alert alert-danger">Dữ liệu nhập không hợp lệ!</div>
    @endif

    <form class="forms-sample" action="{{route('option-post-please-be-late')}}" method="post">
    @csrf  
        {{-- info --}}
        <div class="form-group row">
            <div class="col-sm-9">
                <p>You want to be late!</p>
            </div>
        </div>

        {{-- time --}}
        <div class="form-group row">
            <label for="exampleInputl" class="col-sm-3 col-form-label">Time wants to come</label>
            <div class="col-sm-9">
                <input type="datetime-local" name="from" value="{{date('Y-m-d H:i:s',$date/1000)}}">
            </div>
        </div>

        {{-- reason --}}
        <div class="form-group row">
            <label for="exampleTextarea1" class="col-sm-3 col-form-label">Reason</label>
            <div class="col-sm-9">
                <textarea name="reason" class="form-control" id="exampleTextarea1" rows="4"></textarea>             
            </div>
        </div>

        <button type="submit" class="btn btn-primary mr-2" style="margin-right: 10px">Submit</button>
       
    </form>
</div>   
</div>   
</div>  
</div>
<div class="row">
{{-- please come home soon  --}}
<div class="col-md-6 grid-margin stretch-card">
<div class="card">
<div class="card-body">
    <h4 class="card-title">Come Back Soon</h4>

    {{-- Thông báo lỗi tổng quát--}}
    @if ($errors->any())
    <div class="alert alert-danger">Dữ liệu nhập không hợp lệ!</div>
    @endif

    <form class="forms-sample" action="{{route('option-post-please-come-back-soon')}}" method="post">
    @csrf  
        {{-- info --}}
        <div class="form-group row">
            <div class="col-sm-9">
                <p>You want to come back soon!</p>
            </div>
        </div>

        {{-- time --}}
        <div class="form-group row">
            <label for="exampleInputl" class="col-sm-3 col-form-label">Time you want to come back</label>
            <div class="col-sm-9">
                <input type="datetime-local" name="to" value="{{date('Y-m-d H:i:s',$date/1000)}}">        
            </div>
        </div>

        {{-- reason --}}
        <div class="form-group row">
            <label for="exampleTextarea1" class="col-sm-3 col-form-label">Reason</label>
            <div class="col-sm-9">
                <textarea name="reason" class="form-control" id="exampleTextarea1" rows="4"></textarea>             
            </div>
        </div>

        <button type="submit" class="btn btn-primary mr-2" style="margin-right: 10px">Submit</button>
        
    </form>
</div>   
</div>   
</div>  
{{-- take a break --}}
<div class="col-md-6 grid-margin stretch-card">
<div class="card">
<div class="card-body">
    <h4 class="card-title">Take A Break</h4>

    {{-- Thông báo lỗi tổng quát--}}
    @if ($errors->any())
    <div class="alert alert-danger">Dữ liệu nhập không hợp lệ!</div>
    @endif

    <form class="forms-sample" action="{{route('option-post-take-a-break')}}" method="post">
    @csrf  
        {{-- info --}}
        <div class="form-group row">
            
            <div class="col-sm-9">
                <p>You want to take a break</p>
            </div>
        </div>

        {{-- time --}}
        <div class="form-group row">
            <label for="exampleInputl" class="col-sm-3 col-form-label">Time take a break</label>
            <div class="col-sm-9">
                <input type="date" name="from" value="{{date('Y-m-d',$date/1000)}}">        
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
        
    </form>
</div>   
</div>   
</div> 
</div>
<a href="{{route('client-dashboard')}}" class="btn btn-secondary">Cancel</a>
</div>  
</div>    
@endsection


