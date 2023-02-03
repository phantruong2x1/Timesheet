@extends('backend.layouts.master')

@section('title')
  {{$title}}
@endsection

@php
$currentMonth = strtotime(date('Y-m-15'));
@endphp

@section('content')
<div class="main-panel">
<div class="content-wrapper">
<div class="row">
<div class="col-md-12 grid-margin stretch-card">
<div class="card">

<div class="card-body">
    <h4 class="card-title">Staff Table</h4>
    {{-- Lọc  --}}
    <form action="" method="get">
      <div class="row">
          {{-- Lọc theo tháng --}}
          <div class="col-3">
              <select class="form-control" name="date">     
                @for($i=0;$i<9;$i++)
                  <option
                    {{request()->date==date('m-Y',strtotime('-'.$i.' month', $currentMonth)) ? 'selected':false}} >
                    {{date('m-Y',strtotime('-'.$i.' month', $currentMonth))}} </option>
                @endfor
              </select>
          </div>
          <div class="col-2">
              <button type="submit" id="btn-submit" class="btn btn-outline-primary">Tìm kiếm</button>
          </div>
      </div>
    </form>

    <div class="table-responsive">
      <table class="table table-striped table-borderless">
        <thead>
          <tr>
            <th>#</th>
            <th>Id</th>
            <th>Name</th>
            <th>Total Working Days</th>
            <th>Total Working Hours</th>
            <th>Total Overtime Hours</th>
            <th>Late Checkin</th>
            <th>Early Checkout</th>
            
          </tr>
        </thead>
        <tbody>
            
          {{-- Hiển thị danh sách nhân viên --}}
          @if(!empty($totalList))

            @foreach ($totalList as $key=>$item)
            <tr>

              {{-- Hiển thị dữ liệu --}}
              <td>{{$key+1}}</td>
              <td>{{$item['staff_id']}}</td>
              <td>{{$item['full_name']}}</td>
              <td>{{$item['total_work_days']}}</td>

              @if($item['total_work_hours'] > 0)
                  <td>{{number_format($item['total_work_hours']/3600000, 1)}} h</td>
              @else
                  <td style="color: gainsboro">0 h</td>
              @endif

              @if($item['total_over_hours'] > 0)
                  <td>{{number_format($item['total_over_hours']/3600000, 1)}} h</td>
              @else
                  <td style="color: gainsboro">0 h</td>
              @endif

              <td>{{$item['total_last_checkin']}}</td>
              <td>{{$item['total_early_checkout']}}</td>
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
@endsection

