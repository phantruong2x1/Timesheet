@extends('backend.layouts.master')
@section('title', 'Dashboard')

@php
$currentMonth = strtotime(date('Y-m-15'));
if(empty(request()->date_filter))
    $dateFilter = date('Y-m-d');
else {
    $dateFilter = request()->date_filter;
}
$key = 1;
@endphp

@section('content')
<div class="main-panel">
<div class="content-wrapper">
    <h4 class="font-weight-bold">{{__('sunshine.welcome')}} 
        @if(!empty($userDetail->staff->full_name))
            {{$userDetail->staff->full_name}}
        @else
            {{$userDetail->user_name}}
        @endif
    </h4>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title mb-0">Time Sheet</p>

                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                          @if(Session::has('alert-' . $msg))
                            <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                          @endif
                        @endforeach
                    </div>

                    <br>
                    {{-- Lọc  --}}
                    <form action="{{route('admin-dashboard')}}" id="form_filter" method="get">
                        <div class="row">
                            {{-- Lọc theo ngày --}}
                            <div class="col-2">
                                <input type="date" name="date_filter" class="form-control date_filter" value="{{$dateFilter}}">
                            </div>
                            {{-- Lọc theo Staff Name --}}
                            <div class="col-3">
                                <select class="form-control staff_filter" name="staff_id">
                                <option value="0">All Name</option>
                                @if(!empty($staffsList))
                                    @foreach($staffsList as $item)
                    
                                    <option value="{{$item['id']}}" 
                                        {{request()->staff_id==$item['id'] ? 'selected':false}}>
                                        {{$item->full_name}}
                                    </option>
                    
                                    @endforeach
                                @endif
                                </select>
                            </div>
                           {{-- button add user --}}
                            <div class=" col-7 d-flex justify-content-end">
                                <a href="{{route('timesheets.create')}}" class="btn btn-info btn-sm" style="line-height: 30px">Add <i class="ti-plus"></i></a>
                            </div>
                        </div>
                    </form>
                    {{-- Table Timesheet --}}
                    <div class="table-responsive">
                        <table class="table table-striped table-borderless">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Full Name</th>
                                    <th>Date</th>
                                    <th>First Check-In</th>
                                    <th>Last Check-Out</th>
                                    <th>Working hour</th>
                                    <th>Overtiming</th>
                                    <th>Status</th>
                                    <th>Option</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($timesheetList))
                                @foreach ($timesheetList as $item)
                                
                                <tr>
                                    {{-- Hiển thị dữ liệu --}}
                                    <td class="py-1">
                                        <img src="{{ empty($item->staff->avatar) ? asset('assets/images/avatar-default.png') : asset('assets/avatars/' . $item->staff->avatar)}}" alt="avatar">
                                    </td>
                                    @if(empty($item->staff->full_name))
                                        <td>{{$item['staff_id']}}</td>
                                    @else
                                        <td>{{$item->staff->full_name}}</td>
                                    @endif

                                    <td>{{$item['date']}}</td>
                                    <td>{{date('H:i:s',$item['first_checkin']/1000)}}</td>
                                    
                                    {{-- last_checkout data --}}
                                    @if(!empty($item['last_checkout']))
                                        <td>{{date('H:i:s',$item['last_checkout']/1000)}}</td>
                                    @else
                                        <td style="color: gainsboro">No data!</td>
                                    @endif

                                    {{-- Working_hour data --}}
                                    @if($item['working_hour'] > 0)
                                        <td>{{number_format($item['working_hour']/3600000, 1)}} h</td>
                                    @else
                                        <td style="color: gainsboro">0 h</td>
                                    @endif

                                    {{-- overtime data --}}
                                    @if($item['overtime'] > 0)
                                        <td>{{number_format($item['overtime']/3600000, 1)}} h</td>
                                    @else
                                        <td style="color: gainsboro">0 h</td>
                                    @endif
                                    {{-- status  --}}
                                    <td>
                                        <label class="status badge">{{ (empty($item['status'])) ? 'Pending' : $item['status']}}</label>
                                    </td>
                                    {{-- Nút option --}}
                                    <td>
                                        <a href="{{route('timesheets.edit',['id' => $item['id']])}}" class="btn btn-warning btn-sm"><i class="ti-pencil-alt"></i></a>
                                        <a onclick="return confirm('Are you sure you want to delete?')" href="{{route('timesheets.destroy',['id' => $item['id']])}}" class="btn btn-danger btn-sm">
                                            <i class="ti-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="10">There is no data!</td>
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
        $('.staff_filter').change(function() {
            $('#form_filter').submit();
        })
        $('.date_filter').change(function() {
            $('#form_filter').submit();
        })
    })

    //color status
    const listStatus = document.querySelectorAll('.status')
    for(let i=0; i<listStatus.length; i++){
        let textStatus = listStatus[i].innerText
        if(textStatus == 'Late checkin' || textStatus =='Early checkout' || textStatus == 'Late checkin/Early checkout')
            listStatus[i].classList.add('badge-warning')
        else 
            listStatus[i].classList.add('badge-success')
    }
</script> 

@endsection


