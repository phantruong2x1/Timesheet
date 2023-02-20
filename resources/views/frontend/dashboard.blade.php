@extends('frontend.layouts.master')

@section('title', 'Dashboard')

@php
$currentMonth = strtotime(date('Y-m-15'));
@endphp

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
    {{-- User List Timesheet  --}}
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                {{-- Thông báo --}}
                <div class="flash-message">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                    @if(Session::has('alert-' . $msg))
                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                    @endif
                    @endforeach
                </div>
                
                <div class="card-body row">
                    <p class="col-md-2 card-title mb-0">Time Sheet</p>
                    {{-- Lọc  --}}
                    <form action="{{route('client-dashboard')}}" id="form_filter" method="get" class="col-md-8">
                        <div class="row">
                            {{-- Lọc theo tháng --}}
                            <div class="col-3">
                                <select class="form-control date_filter" name="date_filter">    
                                    @for($i = 0; $i < 9; $i++)
                                    <option
                                        {{request()->date_filter==date('m-Y',strtotime('-'.$i.' month', $currentMonth)) ? 'selected':false}} >
                                        {{date('m-Y',strtotime('-'.$i.' month', $currentMonth))}} 
                                    </option>
                                    @endfor
                                </select>
                            </div>
                                <i class="p-2" style="background-color: #FFFF66; height: 10px;margin-left: 30%;border: 1px solid black"></i> <p class="pl-2"> Today</p>
                                <i class="p-2" style="background-color: #CCFFCC; height: 10px;margin-left: 40px;border: 1px solid black"></i> <p class="pl-2"> Thứ 7</p>
                                <i class="p-2" style="background-color: #FFCCFF; height: 10px;margin-left: 40px;border: 1px solid black"></i> <p class="pl-2"> CN</p>
                        </div>
                    </form>
                    
                    <div class="table-responsive pt-3">
                        <table class="table " id="table1" >
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>First Check-In</th>
                                    <th>Last Check-Out</th>
                                    <th>Working hour</th>
                                    <th>Overtiming</th>
                                    <th>Status</th>
                                    {{-- <th>Leave Status</th> --}}
                                    
                                </tr>
                            </thead>
                            <tbody >
                                @if(!empty($userListTimesheet))
                                @foreach ($userListTimesheet as $key=>$item)
                
                                <tr style = "background-color: {{$item['colorWeekday']}}">
                                    {{-- Hiển thị dữ liệu --}}
                                    <td>{{$key}}</td>
                                    <td><b style="color: #000044	">{{$item['date']}}</b> <span style="color: #AAAAAA"> ({{$item['weekday']}})</span></td>
                                    {{-- first_checkin data --}}
                                    @if(!empty($item['first_checkin']))
                                        <td>{{date('H:i:s',$item['first_checkin']/1000)}}</td>
                                    @else
                                        <td style="color: gainsboro">No data!</td>
                                    @endif
                                    {{-- last_checkout data --}}
                                    @if(!empty($item['last_checkout']))
                                        <td>{{date('H:i:s',$item['last_checkout']/1000)}}</td>
                                    @else
                                        <td style="color: gainsboro">No data!</td>
                                    @endif

                                    {{-- Working_hour data --}}
                                    @if(empty($item['working_hour']))
                                        <td style="color: gainsboro">0 h</td>
                                    @elseif( $item['working_hour'] > 0  )
                                        <td>{{number_format($item['working_hour']/3600000, 1)}} h</td>  
                                    @endif

                                    {{-- overtime data --}}
                                    @if(empty($item['overtime']))
                                        <td style="color: gainsboro">0 h</td>
                                    @elseif($item['overtime'] > 0 && !empty(['overtime']))
                                        <td>{{number_format($item['overtime']/3600000, 1)}} h</td>
                                    @endif

                                    <td>
                                        @if(empty($item['id']))
                                            <label for=""></label>
                                        @elseif(empty($item['status']) )
                                            <label class="badge badge-success">Pending</label>
                                        @elseif(($item['status']) == 'On Time')
                                            <label class="badge badge-success">{{$item['status']}}</label>
                                        @else
                                            <label class="badge badge-warning">{{$item['status']}}</label>
                                        @endif
                                    </td>
                                    {{-- check Leave Status --}}
                                    {{-- <td>
                                    @if(empty($status) || empty($item['id']))
                                        <label></label>
                                    @elseif($status == 'On Time' || $status == 'Pending')
                                        <label class="badge badge-success">OK</label>
                                    @else 
                                        @if($item['leave_status']=='1')
                                            <label class="badge badge-success">Yes</label>
                                        @else
                                            <label class="badge badge-warning">No</label>
                                        @endif
                                    @endif
                                    </td>  --}}
                                    {{-- @if(!empty($item['id']))
                                        <td><a class="btn badge badge-warning" href="{{route('option-make-order',['id' => $item['id'], 'date' => $item['date']])}}">. . .</a></td>
                                    @else
                                        <td><a class="btn badge badge-warning" href="{{route('option-make-order',['id' => -1, 'date' => $item['date']])}}">. . .</a></td>
                                    
                                    @endif --}}
                                    <td>
                                    @if(!empty($item['id']))
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-warning dropdown-toggle dropdown-toggle-split" id="dropdownMenuSplitButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuSplitButton1">
                                                <h6 class="dropdown-header">Option</h6>
                                                <a class="dropdown-item" href="{{route('option-forget',['id' => $item['id']])}}">Update Checkout</a>
                                                <a class="dropdown-item" href="{{route('option-please-be-late',['date' => $item['date']])}}">Please Be Late</a>
                                                <a class="dropdown-item" href="{{route('option-please-come-back-soon',['date' => $item['date']])}}">Please Come Back Soon</a>
                                                <a class="dropdown-item" href="{{route('option-take-a-break',['date' => $item['date']])}}">Take a Break</a>                                           
                                            </div>
                                          </div>   
                                    @else 
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-warning dropdown-toggle dropdown-toggle-split" id="dropdownMenuSplitButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuSplitButton1">
                                                <h6 class="dropdown-header">Option</h6>
                                                <a class="dropdown-item" href="{{route('option-please-be-late',['date' => $item['date']])}}">Please Be Late</a>
                                                <a class="dropdown-item" href="{{route('option-please-come-back-soon',['date' => $item['date']])}}">Please Come Back Soon</a>
                                                <a class="dropdown-item" href="{{route('option-take-a-break',['date' => $item['date']])}}">Take a Break</a>                                           
                                            </div>
                                        </div>  
                                    @endif 
                                    </td>
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
    })

// $(document).ready(function(){

//     $(".date_filter").change(function(){
//         var date_filter = $('.date_filter').val();
//         $.ajax({
//             url: "/client/dashboard/",
//             method: "GET",
//             data: {date_filter: date_filter},
//             success: function(result){
//                 $("#table1").html(result);
//             },
//         });
//     });
// });


</script> 

@endsection

