@extends('frontend.layouts.master')

@section('title', 'Staff Information')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
    <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
    <div class="card-body">
        <h4 class="card-title">Change Password</h4>
    
        {{-- Thông báo lỗi tổng quát--}}
        @if ($errors->any())
        <div class="alert alert-danger">Dữ liệu nhập không hợp lệ!</div>
        @endif
    
        <form class="forms-sample" action="{{route('client.settings.update-password')}}" method="POST">
        @csrf     
            {{-- staff name --}}
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Full Name:</label>
                <label class="col-form-label"><b>{{$userDetail->staff->full_name}}</b></label>
            </div>
    
            {{-- role_id --}}
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Role Name:</label>
                <label class="col-form-label"><b>{{$userDetail->user_role->role_name}}</b></label>
            </div>
    
            {{-- user_name --}}
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">User Name:</label>
                <label class="col-form-label"><b>{{$userDetail->user_name}}</b></label>
            </div>
    
            {{-- password --}}
            <div class="form-group row">
                <label for="exampleInputEmail2" class="col-sm-2 col-form-label">New Password:</label>
                <div class="col-sm-9">
                <input type="password" class="form-control" id="exampleInputEmail2" 
                name="password" value="{{old('password')}}">
    
                    {{-- Thông báo lỗi --}}
                    @error('password')
                    <span style="color: red">{{$message}}</span>
                    @enderror
                    
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

