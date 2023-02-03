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
                <div class="card-body row">
                    <p class="col-md-2 card-title mb-0">Time Sheet</p>
                    {{-- Lọc  --}}
                    <form action="{{route('client-dashboard')}}" method="get" class="col-md-8">
                      <div class="row">
                          {{-- Lọc theo tháng --}}
                          <div class="col-3">
                              <select class="form-control" name="date_filter">    
                                @for($i=0;$i<9;$i++)
                                  <option
                                    {{request()->date_filter==date('m-Y',strtotime('-'.$i.' month', $currentMonth)) ? 'selected':false}} >
                                    {{date('m-Y',strtotime('-'.$i.' month', $currentMonth))}} </option>
                                @endfor
                              </select>
                          </div>
                          <div class="col-2">
                              <button type="submit" id="btn-submit" class="btn btn-outline-primary">Tìm kiếm</button>
                          </div>
                            <i class="p-2" style="background-color: #FFFF66; height: 10px;margin-left: 30%;border: 1px solid black"></i> <p class="pl-2"> Today</p>
                            <i class="p-2" style="background-color: #CCFFCC; height: 10px;margin-left: 40px;border: 1px solid black"></i> <p class="pl-2"> Thứ 7</p>
                            <i class="p-2" style="background-color: #FFCCFF; height: 10px;margin-left: 40px;border: 1px solid black"></i> <p class="pl-2"> CN</p>
                      </div>
                    </form>
                    
                    <div class="table-responsive pt-3">
                        <table class="table " >
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
                                @php
                                    //check null
                                    if(!empty($item['first_checkin']) || !empty($item['last_checkout'])){
                                        $status = "Pending";
                                        $color  = 'badge-success';
                                        
                                        //check late arrival
                                            if(date('H:i:s',$item['first_checkin']/1000) > '08:30:00'){
                                                $status = 'Late checkin';
                                                $color  = 'badge-warning';
                                            }
                                        //check null
                                        if(!empty($item['last_checkout'])){
                                            //check late checkin && early checkout
                                            if( date('H:i:s',$item['first_checkin']/1000) > '08:30:00' && 
                                                date('H:i:s',$item['last_checkout']/1000) <= '17:30:00' ){
                                                $status = 'Late checkin/Early checkout';
                                                $color  = 'badge-warning';
                                            }
                                            //check early checkout
                                            else if(date('H:i:s',$item['last_checkout']/1000) < '17:30:00'){
                                                $status = 'Early checkout';
                                                $color  = 'badge-warning';
                                            }
                                            else if(date('H:i:s',$item['first_checkin']/1000) <= '08:30:00'){
                                                $status = 'On Time';
                                                $color  = 'badge-success';
                                            }
                                        }
                                    }
                                @endphp
                                <tr style = "background-color: {{$item['colorWeekday']}}">
                                    {{-- Hiển thị dữ liệu --}}
                                    <td>{{$key}}</td>
                                    <td><b style="color: #000044	">{{$item['weekday']}}</b> - <span style="color: #AAAAAA"> {{$item['date']}} </span></td>
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

                                    @if(!empty($color) && !empty($item['id']))
                                      <td><label class="badge {{$color}}">{{$status}}</label></td>
                                    @else
                                      <td></td>
                                    @endif
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
                                    @if(!empty($item['id']))
                                      <td><a class="btn badge badge-warning" href="{{route('option-make-order',['id' => $item['id'], 'date' => $item['date']])}}">. . .</a></td>
                                    @else
                                      <td><a class="btn badge badge-warning" href="{{route('option-make-order',['id' => -1, 'date' => $item['date']])}}">. . .</a></td>
                                    
                                    @endif
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
@endsection

