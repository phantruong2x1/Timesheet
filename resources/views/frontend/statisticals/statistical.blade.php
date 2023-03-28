@extends('frontend.layouts.master')
@section('title', 'Statistical')

@php
$currentMonth = strtotime(date('Y-m-15'));
@endphp

@section('content')
<style>
    .table td img, .jsgrid .jsgrid-table td img {
    width: 50px;
    height: 50px;
    border-radius: 0%;
    }
</style>

<div class="main-panel">
    <div class="content-wrapper">
    {{-- User List Timesheet  --}}
    <div class="row">
        <div class="col-md-8 grid-margin stretch-card">
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
                    <p class="col-md-3 card-title mb-0">{{__('sunshine.Time Sheet')}}</p>
                    {{-- Lọc  --}}
                    <form action="{{route('client.statisticals')}}" id="form_filter" method="get" class="col-md-9">
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
                            <div class="col-9 d-flex justify-content-end">
                                <i class="p-2" style="background-color: #e9e951; height: 10px;margin-left: 30%;border: 1px solid black"></i> <p class="pl-2"> {{__('sunshine.Today')}}</p>
                                <i class="p-2" style="background-color: #beecbe; height: 10px;margin-left: 40px;border: 1px solid black"></i> <p class="pl-2"> {{__('sunshine.Saturday')}}</p>
                                <i class="p-2" style="background-color: #f5cbcb; height: 10px;margin-left: 40px;border: 1px solid black"></i> <p class="pl-2"> {{__('sunshine.Sunday')}}</p>
                            </div>
                        </div>
                    </form>
                    
                    <div class="table-responsive pt-3">
                        <table class="table table-hover" id="myTable" >
                            <thead>
                                <tr>
                                    <th>{{__('sunshine.Date')}}</th>
                                    <th>{{__('sunshine.First Check-In')}}</th>
                                    <th>{{__('sunshine.Last Check-Out')}}</th>
                                    <th>{{__('sunshine.Working Hour')}}</th>
                                    <th>{{__('sunshine.Overtiming')}}</th>
                                    <th>{{__('sunshine.Status')}}</th>
                                    {{-- <th>Leave Status</th> --}}
                                    
                                </tr>
                            </thead>
                            <tbody >
                                @if(!empty($userListTimesheet))
                                @foreach ($userListTimesheet as $key=>$item)
                
                                <tr style = "background-color: {{$item['colorWeekday']}}">
                                    {{-- Hiển thị dữ liệu --}}
                                    <td><b style="color: #000044	">{{$item['date']}}</b> <span style="color: #8b8a8a"> ({{$item['weekday']}})</span></td>
                                    <td>
                                        @if(!empty($item['first_checkin']))
                                            {{date('H:i:s',$item['first_checkin']/1000)}}
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($item['last_checkout']))
                                            {{date('H:i:s',$item['last_checkout']/1000)}}
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($item['working_hour']))
                                            {{number_format($item['working_hour']/3600000, 1)}} h
                                        @endif
                                    </td>
                                    <td class="style-number">
                                        @if(!empty($item['overtime']))
                                            {{number_format($item['overtime']/3600000, 1)}} h
                                        @endif
                                    </td>
                                    <td>
                                        @if(empty($item['id']))
                                            <label></label>
                                        @else
                                            <label class="status badge">{{ (empty($item['status'])) ? 'Pending' : $item['status']}}</label>
                                        @endif
                                    </td>
               
                                    <td>
                                    @if(!empty($item['id']))
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm dropdown-toggle dropdown-toggle-split" id="dropdownMenuSplitButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuSplitButton1">
                                                <h6 class="dropdown-header">{{__('sunshine.Option')}}</h6>
                                                <a style="cursor: pointer" class="show-popup-forget forget-button dropdown-item" data-id = "{{$item['id']}}">{{__('sunshine.Update Checkout')}}</a>
                                                <a style="cursor: pointer" class="show-popup-be-late be-late-button dropdown-item" data-date = "{{$item['date']}}">{{__('sunshine.Please Be Late')}}</a>
                                                <a style="cursor: pointer" class="show-popup-come-back-soon come-back-soon-button dropdown-item" data-date = "{{$item['date']}}">{{__('sunshine.Please Come Back Soon')}}</a>
                                                <a style="cursor: pointer" class="show-popup-take-a-break take-a-break-button dropdown-item" data-date = "{{$item['date']}}">{{__('sunshine.Take a Break')}}</a>                                   
                                            </div>
                                          </div>   
                                    @else 
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm dropdown-toggle dropdown-toggle-split" id="dropdownMenuSplitButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuSplitButton1">
                                                <h6 class="dropdown-header">{{__('sunshine.Option')}}</h6>
                                                <a style="cursor: pointer" class="show-popup-be-late be-late-button dropdown-item" data-date = "{{$item['date']}}">{{__('sunshine.Please Be Late')}}</a>
                                                <a style="cursor: pointer" class="show-popup-come-back-soon come-back-soon-button dropdown-item" data-date = "{{$item['date']}}">{{__('sunshine.Please Come Back Soon')}}</a>
                                                <a style="cursor: pointer" class="show-popup-take-a-break take-a-break-button dropdown-item" data-date = "{{$item['date']}}">{{__('sunshine.Take a Break')}}</a>
                                            </div>
                                        </div>  
                                    @endif 
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="9">{{__('sunshine.There is no data')}}!</td>
                                </tr>
                              @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 grid-margin">
            <div class="grid-margin justify-content-end d-flex">
                <button class="show-popup show-detail btn btn-light bg-white">{{__('sunshine.Show detail')}}</button>
            </div>
            <div class="col-md-12 mb-4 stretch-card transparent">
                <div class="card card-tale">
                    <div class="card-body">
                        <p class="mb-4">{{__('sunshine.Total Days')}}</p>
                        <p class="fs-30 mb-2">
                            {{($statisticalDeatil)?$statisticalDeatil->working_date: 0}} {{__('sunshine.days')}}
                        </p>
                        <p>{{__('sunshine.Number of days worked this month.')}}</p>
                        <p>{{($statisticalDeatil)?number_format($statisticalDeatil->working_date*100/$countDaysWork):0}}% ({{$countDaysWork}} {{__('sunshine.days')}})</p>
                        <td class="w-100 px-0">
                            <div class="progress progress-md mx-6">
                                <div class="progress-bar bg-warning" role="progressbar" style="width:{{($statisticalDeatil)?number_format($statisticalDeatil->working_date*100/$countDaysWork):0}}%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </td>
                    </div>
                </div>
            </div>

            <div class="col-md-12  mb-4 stretch-card transparent">
                <div class="card card-light-blue">
                    <div class="card-body">
                        <p class="mb-4">{{__('sunshine.Total Hours Worked')}}</p>
                        <p class="fs-30 mb-2">
                            {{($statisticalDeatil)?number_format($statisticalDeatil->working_hour/3600000,1):0}} {{__('sunshine.hour')}}
                        </p>
                        <p>{{__('sunshine.Number of working hours this month.')}}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-12 stretch-card transparent">
                <div class="card card-dark-blue">
                    <div class="card-body">
                        <p class="mb-4">{{__('sunshine.Total Salary')}}</p>
                        <p class="fs-30 mb-2">
                                {{($statisticalDeatil)?number_format($statisticalDeatil->salary,0,',',' '): 0}} vnđ
                        </p>
                        <p>{{__('sunshine.Estimated salary from the beginning of the month to the present.')}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

@include('frontend.statisticals.show-detail')
@include('frontend.options.please-be-late')
@include('frontend.options.please-come-back-soon')
@include('frontend.options.take-a-break')
@include('frontend.options.forget')
<script>
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

