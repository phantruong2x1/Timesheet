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
    <h4 class="card-title">Department Table</h4>
    {{-- nút thêm --}}
    <div class="col-12 d-flex justify-content-end">
      <a href="{{route('departments.add')}}" class="btn btn-info btn-sm" style="line-height: 30px">Add <i class="ti-plus"></i></a>
    </div>   
    <div class="table-responsive">
      <table class="table table-striped table-borderless">
        <thead>
          <tr>
            <th>#</th>
            <th>Department Name</th>
            <th>Department Description</th>
            <th>Option</th>
          </tr>
        </thead>
        <tbody>
          {{-- Hiển thị danh sách nhân viên --}}
          @if(!empty($departmentsList))
            @foreach ($departmentsList as $key=>$item)
            <tr>
              {{-- Hiển thị dữ liệu --}}
              <td>{{$key+1}}</td>
              <td>{{$item->department_name}}</td>
              <td>{{$item->department_desc}}</td>

              {{-- Nút option --}}
              <td>
                <a href="{{route('departments.edit',['id' => $item->id])}}" class="btn btn-warning btn-sm"><i class="ti-pencil-alt"></i></a>
                <a onclick="return confirm('Are you sure you want to delete?')" 
                href="{{route('departments.delete',['id' => $item->id])}}" class="btn btn-danger btn-sm"><i class="ti-trash"></i></a>
              </td> 
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="4">There is no data!</td>
            </tr>
          @endif
        </tbody>
      </table>
    </div>
</div>
</div>
</div>
</div>
</div>
</div>

@endsection