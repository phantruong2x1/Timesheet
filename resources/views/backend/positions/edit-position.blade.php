@extends('backend.layouts.master')

@section('title')
    {{$title}}
@endsection

@section('content')

<div class="card-body">
    <h4 class="card-title">Edit Position</h4>

    {{-- Thông báo lỗi tổng quát--}}
    @if ($errors->any())
    <div class="alert alert-danger">Dữ liệu nhập không hợp lệ!</div>
    @endif

    <form class="forms-sample" action="{{route('positions.post-edit')}}" method="POST">
    @csrf
        
        {{-- Position Name --}}
        <div class="form-group row">
            <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Position Name</label>
            <div class="col-sm-9">
            <input type="text" class="form-control"  id="exampleInputUsername2" 
            name="position_name"  value="{{old('position_name') ?? $positionDetail->position_name}}">
            </div>
        </div>

        {{-- Position Desc --}}
        <div class="form-group row">
            <label for="exampleInputEmail2" class="col-sm-3 col-form-label">Position Description</label>
            <div class="col-sm-9">
            <input type="text" class="form-control" id="exampleInputEmail2" 
            name="position_desc" value="{{old('position_desc') ?? $positionDetail->position_desc}}">
            </div>
        </div>

      
        <button type="submit" class="btn btn-primary mr-2" style="margin-right: 10px">Submit</button>
        <a href="{{route('positions.index')}}" class="btn btn-secondary">Cancel</a>
    </form>
</div>


    
@endsection


