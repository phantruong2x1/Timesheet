@extends('frontend.layouts.master')

@section('title', 'List Requests')

@php
$currentMonth = strtotime(date('Y-m-15'));
$key =1;
@endphp

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
      {{-- UserTimesheet --}}
      <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
          <div class="card tale-bg card-body">
            <div class="card-people mt-auto">
              <img src="/assets/images/dashboard/people.svg" alt="people">
              <div class="weather-info">
                <p class="fs-30 mt-2 ">{{$dt}}</p>  
                <p>({{__('sunshine.Today')}})</p>
              </div>
              
            </div>

          </div>
        </div>
        <div class="col-md-6 grid-margin transparent">
          <div class="row">
            <div class="col-md-6 mb-4 stretch-card transparent">
              <div class="card card-tale">
                <div class="card-body">
                  <p class="mb-4">{{__('sunshine.Check In')}}</p>
                    <p class="fs-30 mb-2">{{($userTimesheet) ? date('H:i:s',$userTimesheet->first_checkin/1000) : __('sunshine.No data')}}</p>
                  <p>{{__('sunshine.The earliest arrival time.')}}</p>
                </div>
              </div>
            </div>
            <div class="col-md-6 mb-4 stretch-card transparent">
              <div class="card card-dark-blue">
                <div class="card-body">
                  <p class="mb-4">{{__('sunshine.Check Out')}}</p>
                  @if(!empty($userTimesheet->last_checkout))
                    <p class="fs-30 mb-2">{{date('H:i:s',$userTimesheet->last_checkout/1000)}}</p>
                  @else
                    <p class="fs-30 mb-2">{{__('sunshine.No data')}}</p>
                  @endif
                  <p>{{__('sunshine.latest return time.')}}</p>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
              <div class="card card-light-blue">
                <div class="card-body">
                  <p class="mb-4">{{__('sunshine.Option')}}</p>
                    {{-- <a href="{{route('option-forget')}}" class="btn btn-warning mb-2">Forget</a> --}}
                    <a style="cursor: pointer" class="show-popup-be-late be-late-button btn btn-light btn-sm mb-2" data-date = "{{date('d-m-Y')}}">Request for permission to arrive late</a>
                    <a style="cursor: pointer" class="show-popup-come-back-soon come-back-soon-button btn btn-light btn-sm mb-2" data-date = "{{date('d-m-Y')}}">Request for permission to leave early</a>
                    <a style="cursor: pointer" class="show-popup-take-a-break take-a-break-button btn btn-danger btn-sm mb-2" data-date = "{{date('d-m-Y')}}">{{__('sunshine.Take a Break')}}</a>
                                           
                </div>
              </div>
            </div>
            <div class="col-md-6 stretch-card transparent">
              <div class="card card-light-danger">
                <div class="card-body">
                  <p class="mb-4">{{__('sunshine.Work Time')}}</p>
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
                <div class="card-body row">
                    <p class="col-md-2 card-title mb-0">{{__('sunshine.Request List')}}</p>
                    
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
                                <option value="0">{{__('sunshine.All Request')}}</option>
                                <option value="Please Be Late" {{request()->request_type=='Please Be Late' ? 'selected':false}}>{{__('sunshine.Please Be Late')}}</option>
                                <option value="Please Come Back Soon" {{request()->request_type=='Please Come Back Soon' ? 'selected':false}}>{{__('sunshine.Please Come Back Soon')}}</option>
                                <option value="Take A Break" {{request()->request_type=='Take A Break' ? 'selected':false}}>{{__('sunshine.Take A Break')}}</option>
                                <option value="Update Checkout" {{request()->request_type=='Update Checkout' ? 'selected':false}}>{{__('sunshine.Update Checkout')}}</option>
                            </select>
                          </div>
                      </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-striped table-borderless">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{__('sunshine.Request Type')}}</th>
                                    <th>{{__('sunshine.Timesheet Date')}}</th>
                                    <th>{{__('sunshine.Time')}}</th>
                                    <th>{{__('sunshine.Reason')}}</th> 
                                    <th>{{__('sunshine.Time Respond')}}</th>
                                    <th>{{__('sunshine.Status')}}</th>      
                                    <th>{{__('sunshine.Created At')}}</th>
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
                                      <b style="color: #FFC100">{{$item['request_type']}}</b> 
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
                                      <label class="badge badge-danger">{{__('sunshine.Denied')}}</label>
                                    @elseif($item['status'] == '1')
                                      <label class="badge badge-success">{{__('sunshine.Accept')}}</label>
                                    @else
                                      <label class="badge badge-warning">{{__('sunshine.Pending')}}</label>
                                    @endif
                                  </td>
                                  <td>{{$item['created_at']}}</td>
                                  <td>
                                    @if($item['status'] == null)
                                      <a onclick="return confirm('Are you sure you want to delete request?')" 
                                      href="{{route('client.requests.destroy',['id' => $item['id']])}}" class="btn btn-danger btn-sm"><i class="ti-trash"></i></a>
                                    @endif
                                  </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="8">{{__('sunshine.There is no data')}}!</td>
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

@include('frontend.options.please-be-late')
@include('frontend.options.please-come-back-soon')
@include('frontend.options.take-a-break')

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

