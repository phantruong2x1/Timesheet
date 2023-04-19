@extends('frontend.layouts.master')

@section('title', 'Dashboard')

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
    {{-- Welcome --}}
    <div class="row">
        <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
            <h3 class="font-weight-bold">{{__('sunshine.welcome')}} 
                @if(!empty($userDetail->staff->full_name))
                {{$userDetail->staff->full_name}}
                @else
                {{$userDetail->user_name}}
                @endif
            </h3>
            <h6 class="font-weight-normal mb-0">{{__('sunshine.Welcome Digtran members to DGT-Timesheet!')}} <span class="text-primary">{{__('sunshine.Wishing everyone a productive day!')}}</span></h6>
            
            </div>
            <div class="col-12 col-xl-4">
            <div class="justify-content-end d-flex">
                <button style="box-shadow: " class="btn btn-light bg-white">
                    <div id="clock" ></div>
                </button>
            </div>
            </div>
        </div>
        </div>
    </div>
    {{-- User List Timesheet  --}}
    <div class="row">
        <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body row">
                    <p class="col-md-3 card-title mb-0">{{__('sunshine.Time Sheet')}}</p>
                    {{-- Lọc  --}}
                    <form action="{{route('client-dashboard')}}" id="form_filter" method="get" class="col-md-9">
                        <div class="row d-flex justify-content-end">
                            <i class="p-2" style="background-color: #e9e951; height: 10px;margin-left: 30%;border: 1px solid black"></i> <p class="pl-2"> {{__('sunshine.Today')}}</p>
                            <i class="p-2" style="background-color: #beecbe; height: 10px;margin-left: 40px;border: 1px solid black"></i> <p class="pl-2"> {{__('sunshine.Saturday')}}</p>
                            <i class="p-2" style="background-color: #f5cbcb; height: 10px;margin-left: 40px;border: 1px solid black"></i> <p class="pl-2 mr-3"> {{__('sunshine.Sunday')}}</p>
                        </div>
                    </form>
                    
                    <div class="table-responsive pt-3">
                        <table class="table table-hover" id="table1" >
                            <thead>
                                <tr>
                                    <th>#</th>
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
                                    <td>
                                        <div style="position: relative; text-align: center">
                                            <img src="/assets/images/dashboard/lich-png.png" alt="image" style="text-align: center">
                                            <div style="position: absolute; top: 60%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
                                              <b class="style-color">{{$item['weekday']}}</b>
                                            </div>
                                          </div>
                                    </td>
                                    <td>{{$item['date']}}</td>
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
            <div class="col-md-12 mb-4 stretch-card transparent">
                <div class="card card-tale">
                    <div class="card-body">
                        <p class="mb-4">{{__('sunshine.Total Days')}}</p>
                        <p class="fs-30 mb-2">
                            {{($statisticalDeatil)?$statisticalDeatil->working_date: 0}} {{__('sunshine.days')}}
                        </p>
                        <p>{{__('sunshine.Number of days worked this month.')}}</p>
                        <p>{{($statisticalDeatil)?number_format($statisticalDeatil->working_date*100/$countDaysWork,2):0}}% ({{$countDaysWork}} {{__('sunshine.days')}})</p>
                        <td class="w-100 px-0">
                            <div class="progress progress-md mx-6">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: {{($statisticalDeatil)?number_format($statisticalDeatil->working_date*100/$countDaysWork):0}}%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
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
                        {{($statisticalDeatil)?number_format($statisticalDeatil->working_hour/3600000,2):0}} {{__('sunshine.hour')}}
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
@include('frontend.options.please-be-late')
@include('frontend.options.please-come-back-soon')
@include('frontend.options.take-a-break')
@include('frontend.options.forget')

<script>
    $(function() {
        $('.date_filter').change(function() {
            $('#form_filter').submit();
        })
    })

    //current time
    function updateClock() {
        $.get('/current-time', function (data) {
            var time = moment(data.time);
            $('#clock').text(time.format('HH:mm:ss DD-MM-YYYY'));
        });
    }

    $(function () {
        updateClock();
        setInterval(updateClock, 1000);
    });

    //set color: red cho 'CN'
    var elements = document.querySelectorAll('.style-color');
    for (var i = 0; i < elements.length; i++) {
        if(elements[i].innerText == 'CN' || elements[i].innerText=='T7')
        elements[i].style.color = "red";
    }

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

