@extends('frontend.layouts.master')

@section('title', 'Staff Information')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
    <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
    <div class="card-body">
        <h4 class="card-title">{{__('sunshine.Change Password')}}</h4>
    
        {{-- Thông báo lỗi tổng quát--}}
        @if ($errors->any())
        <div class="alert alert-danger">Dữ liệu nhập không hợp lệ!</div>
        @endif
    
        <form class="forms-sample" action="{{route('client.settings.update-password')}}" method="POST">
        @csrf     
            {{-- staff name --}}
            <div class="row">
                <label class="col-sm-2 col-form-label">{{__('sunshine.Full Name')}}:</label>
                <label class="col-sm-10 col-form-label"><b>{{$userDetail->staff->full_name}}</b></label>
            </div>
    
            {{-- role_id --}}
            <div class="row">
                <label class="col-sm-2 col-form-label">{{__('sunshine.Role Name')}}:</label>
                <label class="col-sm-10 col-form-label"><b>{{$userDetail->user_role->role_name}}</b></label>
            </div>
    
            {{-- user_name --}}
            <div class="row">
                <label class="col-sm-2 col-form-label">{{__('sunshine.User Name')}}:</label>
                <label class="col-sm-10 col-form-label"><b>{{$userDetail->user_name}}</b></label>
            </div>
    
            {{-- password --}}
            <div class="row">
                <label for="exampleInputEmail1" class="col-sm-2 col-form-label">{{__('sunshine.Current Password')}}:</label>
                <div class="col-form-label col-sm-10">
                <input type="password" class="form-control " id="exampleInputEmail1" 
                name="current_password" >
    
                    {{-- Thông báo lỗi --}}
                    @error('current_password')
                    <span style="color: red">{{$message}}</span>
                    @enderror
                    
                </div>
            </div>
            <div class="row">
                <label for="exampleInputEmail2" class="col-sm-2 col-form-label">{{__('sunshine.New Password')}}:</label>
                <div class="col-form-label col-sm-10">
                <input type="password" class="form-control " id="exampleInputEmail2" 
                name="new_password" value="{{old('new_password')}}">
    
                    {{-- Thông báo lỗi --}}
                    @error('new_password')
                    <span style="color: red">{{$message}}</span>
                    @enderror
                    
                </div>
            </div>
            <div class="row">
                <label for="exampleInputEmail3" class="col-sm-2 col-form-label">{{__('sunshine.New Password')}}:</label>
                <div class="col-form-label col-sm-10">
                <input type="password" class="form-control " id="exampleInputEmail3" 
                name="new_confirm_password" value="{{old('new_confirm_password')}}">
    
                    {{-- Thông báo lỗi --}}
                    @error('new_confirm_password')
                    <span style="color: red">{{$message}}</span>
                    @enderror
                    
                </div>
            </div>
    
            <button type="submit" class="btn btn-primary mr-2" style="margin-right: 10px">{{__('sunshine.Submit')}}</button>
            <a href="{{route('client-dashboard')}}" class="btn btn-secondary">{{__('sunshine.Cancel')}}</a>
        </form>
    </div>   
    </div>   
    </div>   
    </div>   
    </div>   
</div> 
@endsection

