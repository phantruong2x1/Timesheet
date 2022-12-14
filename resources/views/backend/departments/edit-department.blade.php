@extends('backend.layouts.master')

@section('title')
    {{$title}}
@endsection

@section('content')

<div class="card-body">
    <h4 class="card-title">Edit Department</h4>

    {{-- Thông báo lỗi tổng quát--}}
    @if ($errors->any())
    <div class="alert alert-danger">Dữ liệu nhập không hợp lệ!</div>
    @endif

    <form class="forms-sample" action="{{route('departments.post-edit')}}" method="POST">
    @csrf
        
        <div class="form-group row">
            <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Department Name</label>
            <div class="col-sm-9">
            <input type="text" class="form-control"  id="exampleInputUsername2" 
            name="department_name"  value="{{old('department_name') ?? $departmentDetail->department_name}}">
            </div>
        </div>

        <div class="form-group row">
            <label for="exampleInputEmail2" class="col-sm-3 col-form-label">Department Description</label>
            <div class="col-sm-9">
            <input type="text" class="form-control" id="exampleInputEmail2" 
            name="department_desc" value="{{old('department_desc') ?? $departmentDetail->department_desc}}">
            </div>
        </div>

      
        <button type="submit" class="btn btn-primary mr-2" style="margin-right: 10px">Submit</button>
        <a href="{{route('departments.index')}}" class="btn btn-secondary">Cancel</a>
    </form>
</div>


    
@endsection


