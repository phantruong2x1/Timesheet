@extends('frontend.layouts.master')
@section('title','Be Late')

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

    <form class="forms-sample" action="" method="post">
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
                <input type="datetime-local " name="form">        
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
        <a href="{{route('client-dashboard')}}" class="btn btn-secondary">Cancel</a>
    </form>
</div>   
</div>   
</div>   
</div>   
</div>   
</div>   
@endsection


