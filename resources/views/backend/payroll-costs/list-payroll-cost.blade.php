@extends('backend.layouts.master')

@section('title','Payroll Cost')

@section('content')

<div class="main-panel">
<div class="content-wrapper">
<div class="row">
<div class="col-md-12 grid-margin stretch-card">
<div class="card">

<div class="card-body">
    <h4 class="card-title">Payroll Cost Table</h4>

    {{-- Thông báo lỗi --}}
    <div class="flash-message">
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
        @if(Session::has('alert-' . $msg))
            <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
        @endif
        @endforeach
    </div>

    <div class="table-responsive">
      <table class="table table-striped table-borderless">
        <thead>
          <tr>
            <th>#</th>
            <th>Type Cost</th>
            <th>Cost (vnđ)</th>
            <th>Salary Type</th>
            <th>Option</th>
          </tr>
        </thead>
        <tbody>
          {{-- Hiển thị danh sách nhân viên --}}
          @if(!empty($payrollCostsList))

            @foreach ($payrollCostsList as $key=>$item)
            <tr>

              {{-- Hiển thị dữ liệu --}}
              <td>{{$key+1}}</td>
              <td>{{$item->type_cost}}</td>
              <td>{{$item->cost}}</td>
              <td>{!!$item->salary_type=='Penalty' ?  
                '<label class="badge badge-success">Penalty</label>':
                '<label class="badge badge-danger">Reward</label>'!!}
              </td>

              {{-- Nút option --}}
              <td>
                <button class="show-popup edit-button btn btn-warning btn-sm" data-id="{{$item->id}}"><i class="ti-pencil-alt"></i></button>
                <a onclick="return confirm('Are you sure you want to delete?')" href="{{route('payroll-costs.destroy',['id' => $item->id])}}" class="btn btn-danger btn-sm">
                  <i class="ti-trash"></i>
                </a>        
              </td>
            </tr> 
            @endforeach
            @else
            <tr>
                <td colspan="5">There is no data!</td>
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

@include('backend.payroll-costs.edit-payroll-cost')
  
<script>
    var id =0;
    $(document).ready(function() {
        $(".show-popup").click(function() {
            $(".popup-content").show(200);
            $(".popup-overlay").show();
        });

        $(".close-popup").click(function() {
            $(".popup-content").hide(200);
            $(".popup-overlay").hide();
        });

        $(".edit-button").click(function() {
        id = $(this).data("id");
        $.ajax({
            url: "/admin/payroll-cost/edit/" + id,
            method: "GET",
            success: function(response) {
                // Hiển thị dữ liệu trên popup
                $("#type_cost").val(response.type_cost);
                $("#cost").val(response.cost);
                $("#salary_type").val(response.salary_type);
            }
        });
        });

    });
</script>

@endsection


