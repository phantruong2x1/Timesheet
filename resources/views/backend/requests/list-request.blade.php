@extends('backend.layouts.master')

@section('title')
    {{$title}}
@endsection

@php
$currentMonth = strtotime(date('Y-m-15'));
$key =1;
@endphp

@section('content')
<div class="main-panel">
<div class="content-wrapper">
<div class="row">
<div class="col-md-12 grid-margin stretch-card">
<div class="card">

<div class="card-body">
    <h4 class="card-title">Request Detail Table</h4>

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
    {{--Danh sách yêu cầu chưa xử lý  --}}
    <div class="table-responsive">
      <table class="table table-striped table-borderless">
        <thead>
            <tr>
                <th>#</th>
                <th>Staff Name</th>
                <th>Request Type</th>
                <th>Timesheet Date</th>
                <th>Time</th>
                <th>Reason</th>      
                <th>Created At</th>
                <th>Options</th>
            </tr>
        </thead>

        <tbody>
            @if(!empty($listRequestPeding) )
            @foreach ($listRequestPeding as $key=>$item)
                <tr>
                <td>{{$key + 1}}</td>
                <td>{{$item->staff->full_name}}</td>
                <td>
                @if($item->request_type == 'Update Checkout')
                    <b style="color: green">{{$item->request_type}}</b>                             
                @elseif($item->request_type == 'Take A Break')
                    <b style="color: red">{{$item->request_type}}</b>  
                @else 
                    <b style="color: gold">{{$item->request_type}}</b> 
                @endif
                </td>
                <td>
                    @if(!empty($item->timesheet_date))
                        {{$item->timesheet_date}}
                    @else                                                         
                        <p style="color: gainsboro">No data!</p>
                    @endif
                </td>
                <td>
                    @if(!empty($item->time))
                        {{$item->time}}
                    @else                                                         
                        <p style="color: gainsboro">No data!</p>
                    @endif
                </td>
                <td>
                @if(!empty($item->reason))
                    {{$item->reason}}
                @else
                    <p style="color: gainsboro">No data!</p>
                @endif
                </td>
                <td>{{$item->created_at}}</td>
                
                {{-- Nút option --}}
                <td> 
                    <a onclick="return confirm('Are you sure you want to denied request?')" 
                    href="{{route('requests.denied',['id' => $item->id])}}" class="btn btn-danger btn-sm"><i class="ti-close"></i></a>
                    <a href="{{route('requests.accept',['id' => $item->id])}}" class="btn btn-success btn-sm"><i class="ti-check"></i></a>
                </td>
                </tr>
            @endforeach        
            @else
            <tr>
                <td colspan="8">There is no data!</td>
            </tr>
            @endif
          
            </tbody>
        </table>
    </div>
    {{$listRequestPeding->links()}}
</div>
 {{--Danh sách yêu cầu đã xử lý  --}}
<div class="card-body">
    <h4 class="card-title">History Request Detail Table</h4>
    {{-- Lọc  --}}
    <form action="{{route('requests.index')}}" id="form_filter" method="get">
        <div class="row">
            {{-- Lọc theo tháng --}}
            <div class="col-2">
                <select class="form-control date_filter" name="date_filter">    
                    @for($i=0;$i<9;$i++)
                    <option
                        {{request()->date_filter==date('m-Y',strtotime('-'.$i.' month', $currentMonth)) ? 'selected':false}} >
                        {{date('m-Y',strtotime('-'.$i.' month', $currentMonth))}} </option>
                    @endfor
                </select>
            </div>
            {{-- Lọc theo Staff Name --}}
            <div class="col-3">
                <select class="form-control staff_filter" name="staff_id">
                <option value="0">All Name</option>
                @if(!empty($staffsList))
                    @foreach($staffsList as $item)
    
                    <option value="{{$item->id}}" 
                        {{request()->staff_id==$item->id ? 'selected':false}}>
                        {{$item->full_name}}
                    </option>
    
                    @endforeach
                @endif
                </select>
            </div>
            {{-- Lọc theo request type --}}
            <div class="col-3">
                <select name="request_type" class="form-control request_filter">
                    <option value="0">All Request</option>
                    <option value="Please Be Late" {{request()->request_type=='Please Be Late' ? 'selected':false}}>Please Be Late</option>
                    <option value="Please Come Back Soon" {{request()->request_type=='Please Come Back Soon' ? 'selected':false}}>Please Come Back Soon</option>
                    <option value="Take A Break" {{request()->request_type=='Take A Break' ? 'selected':false}}>Take A Break</option>
                    <option value="Update Checkout" {{request()->request_type=='Update Checkout' ? 'selected':false}}>Update Checkout</option>
                </select>
            </div>
        </div>
    </form>
    {{-- Danh sách yêu cầu đã xử lý --}}
    <div class="table-responsive">
        <table class="table table-striped table-borderless">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Staff Name</th>
                    <th>Request Type</th>
                    <th>Timesheet Date</th>
                    <th>Time</th>
                    <th>Reason</th> 
                    <th>Time Respond</th> 
                    <th>Status</th>    
                    <th>Created At</th>
                </tr>
            </thead>
    
            <tbody>
                @if(!empty($listRequestHistory))
                @foreach ($listRequestHistory as $item)
                    <tr >
                    <td>{{$key++}}</td>
                    <td>{{$item['full_name']}}</td>
                    <td>
                    @if($item['request_type'] == 'Update Checkout')
                        <b style="color: green">{{$item['request_type']}}</b>                             
                    @elseif($item['request_type'] == 'Take A Break')
                        <b style="color: red">{{$item['request_type']}}</b>  
                    @else 
                        <b style="color: gold">{{$item['request_type']}}</b> 
                    @endif
                    </td>
                    <td>
                        @if(!empty($item['timesheet_date']))
                            {{$item['timesheet_date']}}
                        @else                                                         
                            <p style="color: gainsboro">No data!</p>
                        @endif
                    </td>
                    <td>
                        @if(!empty($item['time']) && $item['request_type'] != 'Take A Break')
                            {{$item['time']}}
                        @else                                                         
                            <p style="color: gainsboro">No data!</p>
                        @endif
                    </td>
                    <td>
                    @if(!empty($item['reason']))
                        {{$item['reason']}}
                    @else
                        <p style="color: gainsboro">No data!</p>
                    @endif
                    </td>
                    <td>
                        @if(!empty($item['time_respond']))
                        {{$item['time_respond']}}
                        @else
                        <p style="color: gainsboro">No data!</p>
                        @endif
                    </td>
                    <td>
                        @if($item['status'] == '0')
                        <label class="badge badge-danger">Denied</label>
                        @elseif($item['status'] == '1')
                        <label class="badge badge-success">Accept</label>
                        @else
                        <label class="badge badge-warning">Pending</label>
                        @endif
                    </td>
                    <td>{{$item['created_at']}}</td>
                    </tr>
                @endforeach        
                @else
                <tr>
                    <td colspan="9">There is no data!</td>
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

<script>
    $(function() {
        $('.date_filter').change(function() {
            $('#form_filter').submit();
        })
        $('.request_filter').change(function() {
            $('#form_filter').submit();
        })
        $('.staff_filter').change(function() {
            $('#form_filter').submit();
        })
    })
</script> 

@endsection