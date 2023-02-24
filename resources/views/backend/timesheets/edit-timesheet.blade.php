@extends('backend.layouts.masterEdit')
@section('title')
    {{$title}}
@endsection

@section('content')
<div class="main-panel">
<div class="content-wrapper">
<div class="row">
<div class="col-md-12 grid-margin stretch-card">
<div class="card">
<div class="card-body">
    <h4 class="card-title">Edit Timesheet</h4>

    {{-- Thông báo lỗi tổng quát--}}
    @if ($errors->any())
    <div class="alert alert-danger">Dữ liệu nhập không hợp lệ!</div>
    @endif

    <form class="forms-sample" action="{{route('timesheets.update',$timesheetDetail->id)}}" method="post" >
    @csrf   
        {{-- staff_id --}}
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Staff Name</label>
            <div class="col-sm-9">
            <select class="form-control" name="staff_id" value="{{old('staff_id')}}">

                @foreach($staffsList as $item)
                <option value="{{$item->id}}"

                    @if($timesheetDetail->staff_id == $item->id)
                    selected
                    @endif

                >{{$item->full_name}} - {{$item->id}}</option>
                @endforeach

            </select>
            </div>
        </div>

        {{-- date --}}
        <div class="form-group row">
            <label for="exampleInputUsername1" class="col-sm-3 col-form-label">Date</label>
            <div class="col-sm-9">
            <input type="date" class="form-control"  id="exampleInputUsername1" 
            name="date"  value="{{old('date') ?? date('Y-m-d',strtotime($timesheetDetail->date))}}">

            {{-- Thông báo lỗi --}} 
            @error('date')
            <span style="color: red">{{$message}}</span>
            @enderror

            </div>
        </div>

        {{-- first checkin --}}
        <div class="form-group row">
            <label for="exampleInputUsername2" class="col-sm-3 col-form-label">First Checkin</label>
            <div class="col-sm-9">
            <input type="time" class="form-control"  id="exampleInputUsername2" 
            name="first_checkin"  value="{{old('first_checkin') ?? date('H:i:s',$timesheetDetail->first_checkin/1000)}}">

            {{-- Thông báo lỗi --}}
            @error('first_checkin')
            <span style="color: red">{{$message}}</span>
            @enderror

            </div>
        </div>

        {{-- last checkout --}}
        <div class="form-group row">
            <label for="exampleInputEmail2" class="col-sm-3 col-form-label">Last Checkout</label>
            <div class="col-sm-9">
            <input type="time" class="form-control" id="exampleInputEmail2" 
            name="last_checkout" value="{{old('last_checkout') ?? date('H:i:s',$timesheetDetail->last_checkout/1000)}}">

            {{-- Thông báo lỗi --}}
            @error('last_checkout')
            <span style="color: red">{{$message}}</span>
            @enderror

            </div>
        </div>
        
        {{-- leave_status --}}
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Leave Status</label>
            <div class="col-sm-9">
              <select class="form-control" name="leave_status" >
                <option value="">None</option>
                <option value="1">OK</option>
              </select>
            </div>
        </div>

        <button type="submit" class="btn btn-primary mr-2" style="margin-right: 10px">Submit</button>
        <a href="{{route('admin-dashboard')}}" class="btn btn-secondary">Cancel</a>
    </form>
</div>   
</div>   
</div>   
</div>   
</div>   
</div>   
@endsection


