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
    <h4 class="card-title">Position Table</h4>
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
    {{-- nút thêm --}}
    <div class="col-12 d-flex justify-content-end">
      <a href="{{route('positions.add')}}" class="btn btn-info btn-sm" style="line-height: 30px">Add <i class="ti-plus"></i></a>
    </div>   

    <div class="table-responsive">
      <table class="table table-striped table-borderless">
        <thead>
          <tr>
            <th>#</th>
            <th>Position Name</th>
            <th>Position Description</th>
            <th>Option</th>
          </tr>
        </thead>
        <tbody>
          {{-- Hiển thị danh sách nhân viên --}}
          @if(!empty($positionsList))
            @foreach ($positionsList as $key=>$item)
            <tr>
              {{-- Hiển thị dữ liệu --}}
              <td>{{$key+1}}</td>
              <td>{{$item->position_name}}</td>
              <td>{{$item->position_desc}}</td>
              {{-- Nút option --}}
              <td>
                <a href="{{route('positions.edit',['id' => $item->id])}}" class="btn btn-warning btn-sm"><i class="ti-pencil-alt"></i></a>
                <a onclick="return confirm('Are you sure you want to delete?')" 
                href="{{route('positions.delete',['id' => $item->id])}}" class="btn btn-danger btn-sm"><i class="ti-trash"></i></a>
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
    {{ $positionsList->links() }}
</div>
</div>
</div>
</div>
</div>
</div>

@endsection