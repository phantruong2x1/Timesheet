@extends('frontend.layouts.master')

@section('title', 'List Requests')

@php
$currentMonth = strtotime(date('Y-m-15'));
$key =1;
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
              
            </div>
            <div class="col-12 col-xl-4">
              <div class="justify-content-end d-flex">
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
                    <a href="{{route('option-please-be-late',['date'=>date('Y-m-d H:i:s')])}}" class="btn btn-light mb-2">Please Be Late</a>
                    <a href="{{route('option-please-come-back-soon',['date'=>date('Y-m-d H:i:s')])}}" class="btn btn-light mb-2">Please Come Back Soon</a>
                    <a href="{{route('option-take-a-break',['date'=>date('Y-m-d')])}}" class="btn btn-danger mb-2">Take a Break</a>
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
      {{-- User List Request Details --}}
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
                    <p class="col-md-2 card-title mb-0">Request List</p>
                    
                    {{-- Lọc  --}}
                    <form action="{{route('client.requests.index')}}" id="form_filter" method="get" class="col-md-8">
                      <div class="row">
                          {{-- Lọc theo tháng --}}
                          <div class="col-3">
                              <select class="form-control month_filter" name="date_filter">    
                                @for($i=0;$i<9;$i++)
                                  <option
                                    {{request()->date_filter==date('m-Y',strtotime('-'.$i.' month', $currentMonth)) ? 'selected':false}} >
                                    {{date('m-Y',strtotime('-'.$i.' month', $currentMonth))}} </option>
                                @endfor
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
                                @foreach ($userListRequest as $item)
                                  <tr>
                                    <td>{{$key++}}</td>
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
                                  <td>
                                    @if($item['status'] == null)
                                      <a onclick="return confirm('Are you sure you want to delete request?')" 
                                      href="{{route('client.requests.destroy',['id' => $item['id']])}}" class="btn btn-danger btn-sm">Delete</a>
                                    @endif
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
                </div>
            </div>
        </div>
      </div>
    </div>
</div>

<script>
  $(function() {
    $('.month_filter').change(function() {
      $('#form_filter').submit();
    })
    $('.request_filter').change(function() {
      $('#form_filter').submit();
    })
  })
</script> 

@endsection

