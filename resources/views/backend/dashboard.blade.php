@extends('backend.layouts.master')

@section('title', 'Dashboard')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
    <h3 class="font-weight-bold">{{__('sunshine.welcome')}} {{$userDetail->staff->full_name}}</h3>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title mb-0">Time Sheet</p>
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
                                    <th>Leave Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($timesheetList))

                                @foreach ($timesheetList as $key=>$item)
                                @php
                                    
                                    if(!isset($item->first_checkin)||!isset($item->last_checkout))
                                        {
                                            $status = "Pending";
                                            $color = 'badge-warning';
                                        }
                                        else if($item->first_checkin <= '08:30:00' && $item->last_checkout >= '17:30:00')
                                        {
                                            $status = 'On Time';
                                            $color = 'badge-success';
                                        }
                                        else if($item->first_checkin > '08:30:0' && $item->first_checkin <= '09:00:00' )
                                        {
                                            $status = 'Late arrival';
                                            if($item->last_checkout < '17:30:00')
                                            {
                                                $status = 'Late arrival/Early departure';
                                            }
                                            $color = 'badge-danger';
                                        }
                                        else if($item->last_checkout < '17:30:00')
                                        {
                                            $status = 'Early Departure';
                                        }
                                @endphp
                                <tr>
                    
                                  {{-- Hiển thị dữ liệu --}}
                                  <td>{{$key+1}}</td>
                                  <td>{{$item->staff->full_name}}</td>
                                  <td>{{$item->date}}</td>
                                  <td>{{$item->first_checkin}}</td>
                                  <td>{{$item->last_checkout}}</td>
                                  <td>{{$item->working_hour}}</td>
                                  <td>{{$item->overtime}}</td>
                                  <td><label class="badge {{$color}}">{{$status}}</label></td>
                                  <td>
                                    @if($status == 'On Time')
                                        <label class="badge badge-success">OK</label>
                                    @else 
                                        @if($item->leave_status=='1')
                                            <label class="badge badge-warning">Yes</label>
                                        @else
                                            <label class="badge badge-danger">No</label>
                                        @endif
                                    
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

@endsection


