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
    <h4 class="card-title">Staff Table</h4>

    <p class="card-description">

      <a href="{{route('staff.add')}}" class="btn btn-info">Add Staff</a>
      
    </p>

    {{-- Lọc  --}}
    <form action="" method="get">
      <div class="row">

        {{-- Lọc theo Position --}}
        <div class="col-3">
          <select class="form-control" name="position_id">
            <option value="0">All position </option>
            @if(!empty($positionList))
              @foreach($positionList as $item)

                <option value="{{$item->id}}" 
                  {{request()->position_id==$item->id ? 'selected':false}}>
                  {{$item->position_name}}</option>

              @endforeach
            @endif
          </select>
        </div>

        {{-- Lọc theo Status --}}
        <div class="col-3">
          <select class="form-control" name="status">
            <option value="0">All status</option>
            <option value="enable" {{request()->status=='enable' ? 'selected':false}}>Enable</option>
            <option value="disable" {{request()->status=='disable' ? 'selected':false}}>Disable</option>
          </select>
        </div>

        {{-- Submit --}}
        <div class="col-2">
          <button type="submit" class="btn btn-outline-primary">Tìm kiếm</button>
        </div>
      </div>
    </form>

    <div class="table-responsive">
      <table class="table table-striped table-borderless">
        <thead>
          <tr>
            <th>#</th>
            <th>Id</th>
            <th>Name</th>
            <th>Position</th>
            <th>Gender</th>
            <th>Department</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Type</th>
            <th>Status</th>
            <th>Option</th>
          </tr>
        </thead>
        <tbody>
          {{-- Hiển thị danh sách nhân viên --}}
          @if(!empty($staffsList))

            @foreach ($staffsList as $key=>$item)
            <tr>

              {{-- Hiển thị dữ liệu --}}
              <td>{{$key+1}}</td>
              <td>{{$item->id}}</td>
              <td>{{$item->full_name}}</td>
              <td>{{$item->position->position_name}}</td>
              <td>{{$item->gender}}</td>
              <td>{{$item->department->department_name}}</td>
              <td>{{$item->phone_number}}</td>
              <td>{{$item->email}}</td>
              <td>{{$item->type}}</td>
              <td>{!!$item->status=='disable' ?  
                '<label class="badge badge-danger">Disable</label>':
                '<label class="badge badge-success">Enable</label>'!!}
              </td>

              {{-- Nút option --}}
              <td>
                <a href="{{route('staff.edit',['id' => $item->id])}}" class="btn btn-warning btn-sm">Edit</a>
                <a onclick="return confirm('Are you sure you want to delete?')" href="{{route('staff.delete',['id' => $item->id])}}" class="btn btn-danger btn-sm">Delete</a>
              </td>
              
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="11">There is no data!</td>
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

