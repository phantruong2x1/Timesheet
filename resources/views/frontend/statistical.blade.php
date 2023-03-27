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
    /* pop up */
    .popup-content {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #fff;
    padding: 20px;
    width: 1000px;
    height: 600px;
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
    .close-tab {
    position: absolute;
  
    background: transparent;
    border: none;
    font-size: 20px;
  cursor: pointer;
    }

    .close-tab:hover {
    color: red;
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
                                                <a class="dropdown-item" href="{{route('option-forget',['id' => $item['id']])}}">{{__('sunshine.Update Checkout')}}</a>
                                                <a class="dropdown-item" href="{{route('option-please-be-late',['date' => $item['date']])}}">{{__('sunshine.Please Be Late')}}</a>
                                                <a class="dropdown-item" href="{{route('option-please-come-back-soon',['date' => $item['date']])}}">{{__('sunshine.Please Come Back Soon')}}</a>
                                                <a class="dropdown-item" href="{{route('option-take-a-break',['date' => $item['date']])}}">{{__('sunshine.Take a Break')}}</a>                                           
                                            </div>
                                          </div>   
                                    @else 
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm dropdown-toggle dropdown-toggle-split" id="dropdownMenuSplitButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuSplitButton1">
                                                <h6 class="dropdown-header">{{__('sunshine.Option')}}</h6>
                                                <a class="dropdown-item" href="{{route('option-please-be-late',['date' => $item['date']])}}">{{__('sunshine.Please Be Late')}}</a>
                                                <a class="dropdown-item" href="{{route('option-please-come-back-soon',['date' => $item['date']])}}">{{__('sunshine.Please Come Back Soon')}}</a>
                                                <a class="dropdown-item" href="{{route('option-take-a-break',['date' => $item['date']])}}">{{__('sunshine.Take a Break')}}</a>                                           
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
                            {{($statisticalDeatil)?$statisticalDeatil->working_date: 'No data!'}}
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
                            {{($statisticalDeatil)?number_format($statisticalDeatil->working_hour/3600000,1):0}} h
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


{{-- Pop up xem chi tiết  --}}
<div class="popup-overlay">
    <div class="popup-content" >
        <div class="grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h4 class="card-title">{{__('sunshine.Detailed Table')}}</h4>
                            
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                            <button type="button"  class="close-popup close-tab" >&times;</button>
                        </div>
                    </div>
                    <p class="card-description">
                        {{__('sunshine.Detailed statistics of')}} <span class="text-primary">{{$monthFilter}}</span>
                    </p>
                    <div class="row">
                        <div class="col-5">
                            <div class="form-group row">
                                <div class="col">
                                    <label>{{__('sunshine.Total working day')}}:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                        </div>
                                        <input type="text" id="working_date" class="form-control" name="working_date" readonly
                                        value="{{($statisticalDeatil)?$statisticalDeatil->working_date: 0}}" aria-label="Amount (to the nearest dollar)"/>
                                        <div class="input-group-append">
                                        <span class="input-group-text">{{__('sunshine.day')}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <label>{{__('sunshine.Total working hours')}}:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                        </div>
                                        <input type="text" id="working_hour" class="form-control" name="working_hour" readonly
                                        value="{{($statisticalDeatil)?number_format($statisticalDeatil->working_hour/3600000,1):0}}" aria-label="Amount (to the nearest dollar)"/>
                                        <div class="input-group-append">
                                        <span class="input-group-text">{{__('sunshine.hour')}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col">
                                    <label>{{__('sunshine.Total overtime hours')}}:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                        </div>
                                        <input type="text" id="overtime_hour" class="form-control" name="overtime_hour" readonly
                                        value="{{($statisticalDeatil)?number_format($statisticalDeatil->overtime_hour/3600000,1):0}}" aria-label="Amount (to the nearest dollar)"/>
                                        <div class="input-group-append">
                                        <span class="input-group-text">{{__('sunshine.hour')}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <label>{{__('sunshine.Total last checkin')}}:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                        </div>
                                        <input type="text" id="last_checkin" class="form-control" name="last_checkin" readonly
                                        value="{{($statisticalDeatil)?$statisticalDeatil->last_checkin: 0}}" aria-label="Amount (to the nearest dollar)"/>
                                        <div class="input-group-append">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col">
                                    <label>{{__('sunshine.Total early checkout')}}:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                        </div>
                                        <input type="text" id="early_checkout" class="form-control" name="early_checkout" readonly
                                        value="{{($statisticalDeatil)?$statisticalDeatil->early_checkout: 0}}" aria-label="Amount (to the nearest dollar)"/>
                                        <div class="input-group-append">
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <label>{{__('sunshine.Total day off')}}:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                        </div>
                                        <input type="text" id="day_off" class="form-control" name="day_off" readonly
                                        value="{{($statisticalDeatil)?$statisticalDeatil->day_off: 0}}" aria-label="Amount (to the nearest dollar)"/>
                                        <div class="input-group-append">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col">
                                    <label>{{__('sunshine.Total salary')}}:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text bg-primary text-white">$</span>
                                        </div>
                                        <input type="text" id="salary" class="form-control" name="salary" readonly
                                        value="{{($statisticalDeatil)?number_format($statisticalDeatil->salary,0,',',' '): 0}}" aria-label="Amount (to the nearest dollar)"/>
                                        <div class="input-group-append">
                                        <span class="input-group-text">vnđ</span>
                                        </div>
                                    </div>
                                </div>
                              
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="col">
                                <h4 class="card-title">{{__('sunshine.Payroll Cost Table')}}</h4>
                                <div class="table-responsive">
                                    <table class="table table-striped table-borderless table-hover">
                                        <thead>
                                            <tr>
                                              <th>{{__('sunshine.Type Cost')}}</th>
                                              <th>{{__('sunshine.Value')}}</th>
                                              <th>{{__('sunshine.Cost')}} (vnđ)</th>
                                              <th>{{__('sunshine.Salary Type')}}</th>
                                              <th>{{__('sunshine.Total')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($payroll)
                                            @foreach($payroll as $item)
                                            <tr>
                                                <td>{{$item['name']}}</td>
                                                <td>{{$item['value']}}</td>
                                                <td>{{$item['cost']}}</td>
                                                <td>{!!$item['salary_type'] =='Penalty' ?  
                                                    '<label class="badge badge-success">Penalty</label>':
                                                    '<label class="badge badge-danger">Reward</label>'!!}
                                                </td>
                                                <td>{{$item['total']}}</td>
                                            </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="4"><b>{{__('sunshine.Total salary')}}</b></td>
                                                <td>{{($statisticalDeatil)?number_format($statisticalDeatil->salary,0,',',' '): 0}}</td>
                                            </tr>
                                            @else
                                            <tr>
                                                <td colspan="5">{{__('sunshine.There is no data')}}!</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="justify-content-end d-flex">
                        <a class="btn btn-light close-popup ">{{__('sunshine.Cancel')}}</a>
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

    $(document).ready(function() {
        const time = document.querySelector(".date_filter").value
        $(".show-popup").click(function() {
            $(".popup-content").show(200);
            $(".popup-overlay").show();
        });

        $(".close-popup").click(function() {
            $(".popup-content").hide(200);
            $(".popup-overlay").hide();
        });

    });

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

