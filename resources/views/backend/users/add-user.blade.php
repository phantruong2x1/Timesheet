@extends('backend.layouts.master')
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
    <h4 class="card-title">Add User</h4>

    {{-- Thông báo lỗi tổng quát--}}
    @if ($errors->any())
    <div class="alert alert-danger">Dữ liệu nhập không hợp lệ!</div>
    @endif

    <form class="forms-sample" action="" method="post">
    @csrf     
        {{-- staff_id --}}
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Staff Name</label>
            <div class="col-sm-9">
            <select class="form-control" name="staff_id" value="{{old('staff_id')}}">

                @foreach($staffsList as $item)
                <option value="{{$item->id}}">{{$item->full_name}} - {{$item->id}}</option>
                @endforeach

            </select>
            </div>
        </div>

        {{-- role_id --}}
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Role Name</label>
            <div class="col-sm-9">
            <select class="form-control" name="role_id" value="{{old('role_id')}}">

                @foreach($rolesList as $item)
                <option value="{{$item->id}}">{{$item->role_name}}</option>
                @endforeach

            </select>
            </div>
        </div>

        {{-- user_name --}}
        <div class="form-group row">
            <label for="exampleInputUsername2" class="col-sm-3 col-form-label">User Name</label>
            <div class="col-sm-9">
            <input type="text" class="form-control"  id="exampleInputUsername2" 
            name="user_name"  value="{{old('user_name')}}">

            {{-- Thông báo lỗi --}}
            @error('user_name')
            <span style="color: red">{{$message}}</span>
            @enderror

            </div>
        </div>

        {{-- password --}}
        <div class="form-group row">
            <label for="exampleInputEmail2" class="col-sm-3 col-form-label">Password</label>
            <div class="col-sm-9">
            <input type="password" class="form-control" id="exampleInputEmail2" 
            name="password" value="{{old('password')}}">

            {{-- Thông báo lỗi --}}
            @error('password')
            <span style="color: red">{{$message}}</span>
            @enderror

            </div>
        </div>
        
        {{-- status --}}
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Status</label>
            <div class="col-sm-9">
              <select class="form-control" name="status" >
                <option value="1">Enable</option>
                <option value="0">Disable</option>
              </select>
            </div>
        </div>

        <button type="submit" class="btn btn-primary mr-2" style="margin-right: 10px">Submit</button>
        <a href="{{route('users.index')}}" class="btn btn-secondary">Cancel</a>
    </form>
</div>   
</div>   
</div>   
</div>   
</div>   
</div>   
@endsection


