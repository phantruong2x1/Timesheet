@extends('backend.layouts.master')

@section('title')
    {{$title}}
@endsection

@section('content')

<div class="card-body">
    <h4 class="card-title">Department Table</h4>

    <p class="card-description">

      <a href="{{route('departments.add')}}" class="btn btn-info">Add Department</a>
      
    </p>

    <div class="table-responsive">
      <table class="table">
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
                <a href="{{route('departments.edit',['id' => $item->id])}}" class="btn btn-warning btn-sm">Edit</a>
                <a onclick="return confirm('Are you sure you want to delete?')" 
                href="{{route('departments.delete',['id' => $item->id])}}" class="btn btn-danger btn-sm">Delete</a>
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

@endsection