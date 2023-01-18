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
    <h4 class="card-title">Get data timesheet</h4>

    {{-- Thông báo lỗi tổng quát--}}
    @if ($errors->any())
    <div class="alert alert-danger">Dữ liệu nhập không hợp lệ!</div>
    @endif

    <form class="forms-sample" action="" method="post">
    @csrf     
        
        {{-- user_name --}}
        <div class="form-group row">
            <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Start date (milliseconds)</label>
            <div class="col-sm-9">
            <input type="text" class="form-control"  id="exampleInputUsername2" 
            name="start_date"  >
            <p class="error_msg" style="color: red" id="start_date"></p>

            </div>
        </div>

        <button id="btn-submit" type="button" class="btn btn-primary mr-2" style="margin-right: 10px">Submit</button>
        <a href="{{route('admin-dashboard')}}" class="btn btn-secondary">Cancel</a>
    </form>
</div>   
</div>   
</div>   
</div>   
</div>   
</div> 
<script type="text/javascript">
    $(document).ready(function(){
    $("#btn-submit").click(function(e){
        e.preventDefault();
        var _token = $("input[name='_token']").val();
        var start_date = $("input[name='start_date']").val();

        $.ajax({
            url: '{{route('timesheets.post-time')}}',
            type: 'POST',
            data:{_token:_token,start_date:start_date},
            success: function(data) {
                if($.isEmptyObject(data.errors)){
                    $(".error_msg").html('');
                    alert(data.success);
                }
                else{
                    let resp = data.errors;
                    for(index in resp){
                        $("#"+index).html(resp[index])
                    }
                }
            }
        });
    });
});
</script>
@endsection


