@extends('backend.layouts.master')

@section('title')
  {{$title}}
@endsection

@php
$currentMonth = strtotime(date('Y-m-15'));
@endphp

@section('content')
<style>
  /* sort table  */
  th {
    cursor: pointer;
  }
  th.sort-asc::after {
    content: " ▲";
  }

  th.sort-desc::after {
      content: " ▼";
  }
</style>

<div class="main-panel">
<div class="content-wrapper">
<div class="row">
<div class="col-md-12 grid-margin stretch-card">
<div class="card">

<div class="card-body">
    <h4 class="card-title">Staff Table</h4>
    {{-- Lọc  --}}
      <div class="row">
        {{-- Lọc theo tháng --}}
        
          <div class="col-2">
            <form action="{{route('statisticals.index')}}" id="form_month_filter" method="get">
              <select class="form-control date_filter" name="date">     
                @for($i=0;$i<9;$i++)
                  <option
                    {{request()->date==date('m-Y',strtotime('-'.$i.' month', $currentMonth)) ? 'selected':false}} >
                    {{date('m-Y',strtotime('-'.$i.' month', $currentMonth))}} </option>
                @endfor
              </select>
            </form>
          </div>
        
        <div class="col-8">
          {{-- Ngày bắt đầu lọc --}}
          <form action="{{route('statisticals.index')}}" id="form_date_filter" method="get">
            <div class="row">
              <p style="line-height: 45px" class="ml-2 pr-1">Start date:</p>
              <div class="">
                <input type="date" class="form-control start_date_filter" name="start_date_filter" value="{{request()->start_date_filter}}">
              </div>

              {{-- Ngày kết thúc lọc --}}
              <p style="line-height: 45px" class="ml-4 pr-1">End date:</p>
              <div class="">
                <input type="date" class="form-control end_date_filter" name="end_date_filter" value="{{request()->end_date_filter}}">
              </div>
            </div>
          </form>
        </div>
      </div>
    <div class="table-responsive">
      <table class="table table-striped table-borderless" id="myTable">
        <thead>
          <tr>
            <th onclick="sortTable(0)">#</th>
            <th onclick="sortTable(1)">Id</th>
            <th onclick="sortTable(2)">Name</th>
            <th onclick="sortTable(3)">Total Working Days</th>
            <th onclick="sortTable(4)">Total Working Hours</th>
            <th onclick="sortTable(5)">Total Overtime Hours</th>
            <th onclick="sortTable(6)">Late Checkin</th>
            <th onclick="sortTable(7)">Early Checkout</th>
            <th onclick="sortTable(8)">Total Salary</th>
          </tr>
        </thead>
        <tbody>
            
          {{-- Hiển thị danh sách nhân viên --}}
          @if(!empty($listStatistical))

            @foreach ($listStatistical as $key=>$item)
            <tr>

              {{-- Hiển thị dữ liệu --}}
              <td>{{$key+1}}</td>
              <td>{{$item['staff_id']}}</td>
              <td>{{($item['full_name']) ? $item['full_name'] : $item->staff->full_name}}</td>
              <td>{{$item['working_date']}}</td>

              @if($item['working_hour'] > 0)
                  <td>{{number_format($item['working_hour']/3600000, 1)}} h</td>
              @else
                  <td style="color: gainsboro">0 h</td>
              @endif

              @if($item['overtime_hour'] > 0)
                  <td>{{number_format($item['overtime_hour']/3600000, 1)}} h</td>
              @else
                  <td style="color: gainsboro">0 h</td>
              @endif

              <td>{{$item['last_checkin']}}</td>
              {{-- @if($item['count_minute_last_checkin'] > 0)
              <td>
                {{gmdate('H:i:s',$item['count_minute_last_checkin']/1000)}}
              </td>
              @else
                  <td style="color: gainsboro">0 h</td>
              @endif --}}
              
              <td>{{$item['early_checkout']}}</td>
              <td>{{($item['salary'] > 0) ? number_format($item['salary'],0,',',' ') : 0}}</td>
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
      $('.date_filter').change(function() {
          $('#form_month_filter').submit();
      })
      $('.end_date_filter').change(function() {
          $('#form_date_filter').submit();
      })
  })

  // sắp xếp bảng dữ liệu
  function sortTable(n) {
      var table,
          rows,
          switching,
          i,
          x,
          y,
          shouldSwitch,
          dir,
          switchcount = 0;
      table = document.getElementById("myTable");
      switching = true;
      dir = "asc";
      while (switching) {
          switching = false;
          rows = table.rows;
          for (i = 1; i < rows.length - 1; i++) {
          shouldSwitch = false;
          x = rows[i].getElementsByTagName("td")[n];
          y = rows[i + 1].getElementsByTagName("td")[n];
          if (dir == "asc") {
              if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
              shouldSwitch = true;
              break;
              }
          } else if (dir == "desc") {
              if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
              shouldSwitch = true;
              break;
              }
          }
          }
          if (shouldSwitch) {
          rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
          switching = true;
          switchcount++;
          } else {
          if (switchcount == 0 && dir == "asc") {
              dir = "desc";
              switching = true;
          }
          }
      }
      // Xác định đánh dấu sắp xếp trên tiêu đề đang được chọn
      var th = table.getElementsByTagName("th");
      for (i = 0; i < th.length; i++) {
          th[i].classList.remove("sort-asc");
          th[i].classList.remove("sort-desc");
      }
      if (dir == "asc") {
          th[n].classList.add("sort-asc");
      } else {
          th[n].classList.add("sort-desc");
      }
  }
</script> 

@endsection

