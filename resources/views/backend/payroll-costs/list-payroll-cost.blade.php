@extends('backend.layouts.master')

@section('title','Payroll Cost')

@section('content')
<style>
.popup-content {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #fff;
    padding: 20px;
    width: 700px;
    height: 450px;
    z-index: 10000;
    border-radius: 20px;

}

/* Hiển thị popup khi được kích hoạt */
.popup-overlay{
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 9999;
  /* transition: opacity 0.9s ease; */
}

</style>
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

{{-- Edit table  --}}
<div class="popup-overlay">
    <div class="popup-content" >
        <div class="grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Payroll Cost</h4>
                    <p class="card-description">
                        You can only edit the amount.
                    </p>
                    <form class="forms-sample" action="{{route('payroll-costs.update')}}" id="edit-form" method="POST">
                    @csrf
                    <div class="form-group row">
                        <label for="type_cost" class="col-sm-3 col-form-label">Type Cost</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" id="type_cost" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="cost" class="col-sm-3 col-form-label">Cost</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                <span class="input-group-text bg-primary text-white">$</span>
                                </div>
                                <input type="text" id="cost" class="form-control" name="cost" value="{{old('cost')}}" aria-label="Amount (to the nearest dollar)"/>
                                <div class="input-group-append">
                                <span class="input-group-text">vnđ</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="salary_type" class="col-sm-3 col-form-label">Salary Type</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" id="salary_type" readonly>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mr-2" >Submit</button>
                    <a class="btn btn-light close-popup">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
  
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


