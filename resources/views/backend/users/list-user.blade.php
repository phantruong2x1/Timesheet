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
    <h4 class="card-title">User Table</h4>

    <!-- Đây là div hiển thị Kết quả (thành công, thất bại) sau khi thực hiện các chức năng Thêm, Sửa, Xóa.
    - Div này chỉ hiển thị khi trong Session có các key `alert-*` từ Controller trả về. 
    - Sử dụng các class của Bootstrap "danger", "warning", "success", "info" để hiển thị màu cho đúng với trạng thái kết quả.
    -->
    <div class="flash-message">
      @foreach (['danger', 'warning', 'success', 'info'] as $msg)
        @if(Session::has('alert-' . $msg))
          <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
        @endif
      @endforeach
    </div>

    <p class="card-description">

      <a href="{{route('users.add')}}" class="btn btn-info">Add User</a>
      
    </p>

    <div class="table-responsive">
      <table class="table table-striped table-borderless">
        <thead>
          <tr>
            <th>#</th>
            <th>Staff</th>
            <th>Role</th>
            <th>User Name</th>            
            {{-- <th>Password</th> --}}
            <th>Status</th>
            <th>Option</th>
          </tr>
        </thead>

        <tbody>
          {{-- Hiển thị danh sách nhân viên --}}
          @if(!empty($usersList))

            @foreach ($usersList as $key=>$item)

            <tr>
             
                {{-- Hiển thị dữ liệu --}}
                <td>{{$key+1}}</td>
                <td>@if(!empty($item->staff->full_name)){{$item->staff->full_name}}@endif</td>
                <td>@if(!empty($item->user_role->role_name)){{$item->user_role->role_name}}@endif</td>
                <td>{{$item->user_name}}</td>
                {{-- <td>{{$item->password}}</td> --}}
                <td>{!!$item->status == 1 ?  
                    '<label class="badge badge-success">Enable</label>':
                    '<label class="badge badge-danger">Disable</label>'!!}
                  </td>
                {{-- Nút option --}}
                <td>
                    <a href="{{route('users.edit',['id' => $item->id])}}" class="btn btn-warning btn-sm">Edit</a>
                    <a onclick="return confirm('Are you sure you want to delete?')" 
                    href="{{route('users.delete',['id' => $item->id])}}" class="btn btn-danger btn-sm">Delete</a>
                </td>
              
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="6">There is no data!</td>
            </tr>
          @endif
          
          </tbody>
      </table>
    </div>
    {{ $usersList->links() }}
</div>
</div>
</div>
</div>
</div>
</div>


@endsection