@extends('frontend.layouts.master')

@section('title', 'Dashboard')

@php
$currentMonth = strtotime(date('Y-m-15'));
@endphp

@section('content')
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
              <h6 class="font-weight-normal mb-0">Welcome Digtran members to DGT-Timesheet! <span class="text-primary">Wishing everyone a productive day!</span></h6>

              {{-- Thông báo --}}
              <div class="flash-message">
                @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                  @if(Session::has('alert-' . $msg))
                    <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                  @endif
                @endforeach
              </div>
              
            </div>
            <div class="col-12 col-xl-4">
             <div class="justify-content-end d-flex">
              {{-- <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
                <button class="btn btn-sm btn-light bg-white dropdown-toggle" type="button" id="dropdownMenuDate2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                 <i class="mdi mdi-calendar"></i> Today (10 Jan 2021)
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuDate2">
                  <a class="dropdown-item" href="#">January - March</a>
                  <a class="dropdown-item" href="#">March - June</a>
                  <a class="dropdown-item" href="#">June - August</a>
                  <a class="dropdown-item" href="#">August - November</a>
                </div>
              </div> --}}
             </div>
            </div>
          </div>
        </div>
      </div>
      {{-- UserTimesheet --}}
      <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
          <div class="card tale-bg card-body">
            <div class="card-people mt-auto">
              <img src="/assets/images/dashboard/people.svg" alt="people">
              <div class="weather-info">
                <p class="fs-30 mb-2 ">{{$dt}}</p>  
                <p>(Today)</p>
              </div>
              
            </div>
            {{-- <h4 class="mb4">
              Mã nhân viên: {{$user->staff->id}}
              <br><br>
              Tên nhân viên: {{$user->staff->full_name}}
            </h4> --}}
          </div>
        </div>
        <div class="col-md-6 grid-margin transparent">
          <div class="row">
            <div class="col-md-6 mb-4 stretch-card transparent">
              <div class="card card-tale">
                <div class="card-body">
                  <p class="mb-4">Check In</p>
                    <p class="fs-30 mb-2">{{date('H:i:s',$userTimesheet->first_checkin/1000)}}</p>
                  <p>Sớm nhất</p>
                </div>
              </div>
            </div>
            <div class="col-md-6 mb-4 stretch-card transparent">
              <div class="card card-dark-blue">
                <div class="card-body">
                  <p class="mb-4">Check Out</p>
                  @if(!empty($userTimesheet->last_checkout))
                    <p class="fs-30 mb-2">{{date('H:i:s',$userTimesheet->last_checkout/1000)}}</p>
                  @else
                    <p class="fs-30 mb-2">No data!</p>
                  @endif
                  <p>Muộn nhất</p>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
              <div class="card card-light-blue">
                <div class="card-body">
                  <p class="mb-4">Option</p>
                    {{-- <a href="{{route('option-forget')}}" class="btn btn-warning mb-2">Forget</a> --}}
                    <a href="{{route('option-please-be-late')}}" class="btn btn-light mb-2">Please Be Late</a>
                    <a href="{{route('option-please-come-back-soon')}}" class="btn btn-light mb-2">Please Come Back Soon</a>
                    <a href="{{route('option-take-a-break')}}" class="btn btn-danger mb-2">Take a Break</a>
                </div>
              </div>
            </div>
            <div class="col-md-6 stretch-card transparent">
              <div class="card card-light-danger">
                <div class="card-body">
                  <p class="mb-4">Work Time</p>
                  <p class="fs-30 mb-2">
                  @if(!empty($userTimesheet->working_hour))
                    <p class="fs-30 mb-2">{{number_format($userTimesheet->working_hour/3600000,1)}} (h)</p>
                  @else
                    <p class="fs-30 mb-2">0(h)</p>
                  @endif</p>
                  <p></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div> 
      {{-- User List History Timesheet  --}}
      <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title mb-0">History Time Sheet</p>
                       {{-- Lọc  --}}
                      <form action="{{route('client-dashboard')}}" method="get">
                        <div class="row">
                            {{-- Lọc theo tháng --}}
                            <div class="col-3">
                                <select class="form-control" name="date_filter">    
                                  @for($i=0;$i<5;$i++)
                                    <option
                                      {{request()->date_filter==date('m-Y',strtotime('-'.$i.' month', $currentMonth)) ? 'selected':false}} >
                                      {{date('m-Y',strtotime('-'.$i.' month', $currentMonth))}} </option>
                                  @endfor
                                </select>
                            </div>
                            <div class="col-2">
                                <button type="submit" id="btn-submit" class="btn btn-outline-primary">Tìm kiếm</button>
                            </div>
                        </div>
                      </form>
                    <div class="table-responsive" style="max-height: 600px;">
                        <table class="table table-striped table-borderless" >
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>First Check-In</th>
                                    <th>Last Check-Out</th>
                                    <th>Working hour</th>
                                    <th>Overtiming</th>
                                    <th>Status</th>
                                    <th>Leave Status</th>
                                    
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
                                <tr>
                                    {{-- Hiển thị dữ liệu --}}
                                    <td>{{$key}}</td>
                                    <td>{{$item['date']}}</td>
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
                                    <td>
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
                                    </td> 
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
      {{-- User List Request Details --}}
      <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title mb-0">Request List</p>

                    <div class="table-responsive">
                        <table class="table table-striped table-borderless">
                            <thead>
                                <tr>
                                    <th>#</th>
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
                                @if(!empty($userListRequest))
                                @foreach ($userListRequest as $key=>$item)
                                  <tr>
                                    <td>{{$key+1}}</td>
                                  <td>
                                    @if($item->request_type == 'Update Checkout')
                                      <b style="color: green">{{$item->request_type}}</b>                             
                                    @elseif($item->request_type == 'Take a break')
                                      <b style="color: red">{{$item->request_type}}</b>  
                                    @else 
                                      <b style="color: gold">{{$item->request_type}}</b> 
                                    @endif
                                  </td>
                                  <td>
                                    @if(!empty($item->timesheet->date))
                                      {{date('d-m-Y',$item->timesheet->date/1000)}}
                                    @elseif($item->request_type == 'Please be late' || $item->request_type == 'Take a break')
                                      {{date('d-m-Y',$item->from/1000)}}
                                    @elseif($item->request_type == 'Please come back soon')
                                      {{date('d-m-Y',$item->to/1000)}}  
                                    @else                                                         
                                      <p style="color: gainsboro">No data!</p>
                                    @endif
                                  </td>
                                  <td>
                                    @if($item->request_type == 'Please be late')
                                        {{date('H:i:s',$item->from/1000)}}
                                    @elseif($item->request_type == 'Please come back soon')
                                        {{date('H:i:s',$item->to/1000)}}    
                                    @elseif($item->request_type == 'Update Checkout')
                                        {{date('H:i:s',$item->timesheet->last_checkout/1000)}}  
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
                                  <td>
                                    @if(!empty($item->time_respond))
                                      {{$item->time_respond}}
                                    @else
                                      <p style="color: gainsboro">No data!</p>
                                    @endif
                                  </td>
                                  <td>
                                    @if($item->status == '0')
                                      <label class="badge badge-danger">Denied</label>
                                    @elseif($item->status == '1')
                                      <label class="badge badge-success">Accept</label>
                                    @else
                                      <label class="badge badge-warning">Pending</label>
                                    @endif
                                  </td>
                                  <td>{{$item->created_at}}</td>
                                  <td>
                                    @if($item->status == null)
                                      <a onclick="return confirm('Are you sure you want to delete request?')" 
                                      href="{{route('client.requests.destroy',['id' => $item->id])}}" class="btn btn-danger btn-sm">Delete</a>
                                    @endif
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
                    {{$userListRequest->links()}}
                </div>
            </div>
        </div>
      </div>
    </div>
</div>
@endsection

