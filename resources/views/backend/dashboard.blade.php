@extends('backend.layouts.master')
@section('title', 'Dashboard')

@section('content')
<div class="main-panel">
<div class="content-wrapper">
    <h3 class="font-weight-bold">{{__('sunshine.welcome')}} 
        @if(!empty($userDetail->staff->full_name))
            {{$userDetail->staff->full_name}}
        @else
            {{$userDetail->user_name}}
        @endif
    </h3>
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
                    <form action="" method="get">
                        <div class="row">
                            {{-- Lọc theo Staff Name --}}
                            <div class="col-3">
                                <select class="form-control" name="staff_id">
                                <option value="0">All Name</option>
                                @if(!empty($staffsList))
                                    @foreach($staffsList as $item)
                    
                                    <option value="{{$item->id}}" 
                                        {{request()->staff_id==$item->id ? 'selected':false}}>
                                        {{$item->full_name}}
                                    </option>
                    
                                    @endforeach
                                @endif
                                </select>
                            </div>
                            <div class="col-2">
                                <button type="submit" id="btn-submit" class="btn btn-outline-primary">Tìm kiếm</button>
                            </div>
                            <div class=" col-6 d-flex justify-content-end">
                                <a href="{{route('timesheets.create')}}" class="btn btn-info">Add User</a>
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
                                    <th>Leave Status</th>
                                    <th>Option</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($timesheetsList))
                                @foreach ($timesheetsList as $key=>$item)
                                @php
                                    //check null
                                    if(!empty($item->first_checkin) || !empty($item->last_checkout)){
                                        $item->leave_status = "Pending";
                                        $status = "Pending";
                                        $color  = 'badge-success';
                                        
                                        //check late arrival
                                            if(date('H:i:s',$item->first_checkin/1000) > '08:30:00'){
                                                $status = 'Late checkin';
                                                $color  = 'badge-warning';
                                            }
                                        //check null
                                        if(!empty($item->last_checkout)){
                                            //check late checkin && early checkout
                                            if( date('H:i:s',$item->first_checkin/1000) > '08:30:00' && 
                                                date('H:i:s',$item->last_checkout/1000) <= '17:30:00' ){
                                                $status = 'Late checkin/Early checkout';
                                                $color  = 'badge-warning';
                                            }
                                            //check early checkout
                                            else if(date('H:i:s',$item->last_checkout/1000) < '17:30:00'){
                                                $status = 'Early checkout';
                                                $color  = 'badge-warning';
                                            }
                                            else if(date('H:i:s',$item->first_checkin/1000) <= '08:30:00'){
                                                $status = 'On Time';
                                                $color  = 'badge-success';
                                            }
                                            
                                        }
                                    }
                                @endphp
                                <tr>
                                    {{-- Hiển thị dữ liệu --}}
                                    <td>{{$key+1}}</td>
                                    @if(empty($item->staff->full_name))
                                        <td>{{$item->staff_id}}</td>
                                    @else
                                        <td>{{$item->staff->full_name}}</td>
                                    @endif

                                    <td>{{date('d-m-Y',$item->date/1000)}}</td>
                                    <td>{{date('H:i:s',$item->first_checkin/1000)}}</td>
                                    
                                    {{-- last_checkout data --}}
                                    @if(!empty($item->last_checkout))
                                        <td>{{date('H:i:s',$item->last_checkout/1000)}}</td>
                                    @else
                                        <td style="color: gainsboro">No data!</td>
                                    @endif

                                    {{-- Working_hour data --}}
                                    @if($item->working_hour > 0)
                                        <td>{{number_format($item->working_hour/3600000, 1)}} h</td>
                                    @else
                                        <td style="color: gainsboro">0 h</td>
                                    @endif

                                    {{-- overtime data --}}
                                    @if($item->overtime > 0)
                                        <td>{{number_format($item->overtime/3600000, 1)}} h</td>
                                    @else
                                        <td style="color: gainsboro">0 h</td>
                                    @endif
                                    
                                    <td><label class="badge {{$color}}">{{$status}}</label></td>
                                    
                                    {{-- check Leave Status --}}
                                    <td>
                                    @if($status == 'On Time' || $status == 'Pending')
                                        <label class="badge badge-success">OK</label>
                                    @else 
                                        @if($item->leave_status=='1')
                                            <label class="badge badge-success">Yes</label>
                                        @else
                                            <label class="badge badge-warning">No</label>
                                        @endif
                                    @endif
                                    </td> 
                                    {{-- Nút option --}}
                                    <td>
                                        <a href="{{route('timesheets.edit',['id' => $item->id])}}" class="btn btn-warning btn-sm">Edit</a>
                                        <a onclick="return confirm('Are you sure you want to delete?')" href="{{route('timesheets.destroy',['id' => $item->id])}}" class="btn btn-danger btn-sm">Delete</a>
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
        {{$timesheetsList->links()}}
    </div>
</div>
    
</div>
@endsection


