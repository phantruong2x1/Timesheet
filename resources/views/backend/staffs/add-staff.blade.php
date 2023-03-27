@extends('backend.layouts.master')

@section('title')
  {{$title}}
@endsection

@section('content')
<div class="main-panel">
<div class="content-wrapper">
<div class="row">
<div class="col-md-12 grid-margin stretch-card">
<div class="card">
<div class="card-body">
    {{-- Thông báo lỗi tổng quát--}}
    @if ($errors->any())
    <div class="alert alert-danger">Dữ liệu nhập không hợp lệ!</div>
    @endif

    <h4 class="card-title">Add Staff</h4>

    <form class="form-sample" method="POST">
    @csrf
      <div class="row">

        {{-- Nhập id staff --}}
        <div class="col-md-6">
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">ID Staff</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="id" value="{{old('id')}}" />

              {{-- Thông báo lỗi --}}
              @error('id')
              <span style="color: red">{{$message}}</span>
              @enderror

            </div>
          </div>
        </div>

        {{-- Nhập tên --}}
        <div class="col-md-6">
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Full Name</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="full_name" value="{{old('full_name')}}"/>

              {{-- Thông báo lỗi --}}
              @error('full_name')
              <span style="color: red">{{$message}}</span>
              @enderror

            </div>
          </div>
        </div>
      </div>

      <div class="row">

        {{-- Nhập giới tính --}}
        <div class="col-md-6">
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Gender</label>
            <div class="col-sm-4">
              <div class="form-check">
                <label class="form-check-label"  >
                  <input name="gender" type="radio" class="form-check-input" id="membershipRadios1" value="Nam" checked>
                  Male
                </label>
              </div>
            </div>
            <div class="col-sm-5">
              <div class="form-check">
                <label class="form-check-label">
                  <input name="gender" type="radio" class="form-check-input" id="membershipRadios2" value="Nữ">
                  Female
                </label>
              </div>
            </div>
          </div>
        </div>

        {{-- Nhập ngày sinh --}}
        <div class="col-md-6">
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Date of Birth</label>
            <div class="col-sm-9">
              <input type="date" class="form-control" name="birthday" value="{{old('birthday')}}"/>
            </div>
          </div>
        </div>
      </div>

      <div class="row">

        {{-- Nhập địa chỉ (quê quán) --}}
        <div class="col-md-6">
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Address </label>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="address" value="{{old('address')}}"/>
            </div>
          </div>
        </div>

        {{-- Nhập email --}}
        <div class="col-md-6">
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Email</label>
            <div class="col-sm-9">
              <input type="email" class="form-control" name="email" value="{{old('email')}}"/>

              {{-- Thông báo lỗi --}}
              @error('email')
              <span style="color: red">{{$message}}</span>
              @enderror

            </div>
          </div>
        </div>
      </div>
      <div class="row">

        {{-- Nhập số điện thoại --}}
        <div class="col-md-6">
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Phone Number</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="phone_number" value="{{old('phone_number')}}" />
            </div>
          </div>
        </div>

        {{-- Nhập tax code --}}
        <div class="col-md-6">
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Tax Code</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="tax_code" value="{{old('tax_code')}}"/>
            </div>
          </div>
        </div>
      </div>
      <div class="row">

        {{-- Nhập email company --}}
        <div class="col-md-6">
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Email company</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" value="company@gmail.com" name="email_company" value="{{old('email_company')}}"/>
            </div>
          </div>
        </div>

        {{-- Nhập loại nhân viên --}}
        <div class="col-md-6">
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Type</label>
            <div class="col-sm-9">
              <select class="form-control" name="type" value="{{old('type')}}">
                <option>Part-Time</option>
                <option>Full-Time</option>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="row">

        {{-- Nhập thời gian bắt đầu hợp đồng --}}
        <div class="col-md-6">
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Begin Time</label>
            <div class="col-sm-9">
              <input type="date" class="form-control" name="begin_time" value="{{old('begin_time')}}" />
            </div>
          </div>
        </div>

        {{-- Nhập thời gian kết thúc hợp đồng --}}
        <div class="col-md-6">
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">End Time</label>
            <div class="col-sm-9">
              <input type="date" class="form-control" name="end_time" value="{{old('end_time')}}"/>
            </div>
          </div>
        </div>
      </div>

      <div class="row">

        {{-- Nhập thời gian chính thức làm --}}
        <div class="col-md-6">
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Official Time</label>
            <div class="col-sm-9">
              <input type="date" class="form-control" name="official_time" value="{{old('official_time')}}" />
            </div>
          </div>
        </div>

        {{-- Nhập phòng ban --}}
        <div class="col-md-6">
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Department</label>
            <div class="col-sm-9">
              <select class="form-control" name="department_id" value="{{old('department_id')}}">
                
                @foreach($departmentList as $item)
                <option value="{{$item->id}}">{{$item->department_name}}</option>
                @endforeach

              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="row">

        {{-- Nhập chức vụ, vị trí --}}
        <div class="col-md-6">
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Position</label>
            <div class="col-sm-9">
              <select class="form-control" name="position_id" value="{{old('position_id')}}">

                @foreach($positionList as $item)
                <option value="{{$item->id}}">{{$item->position_name}}</option>
                @endforeach

              </select>
            </div>
          </div>
        </div>

        {{-- Nhập trạng thái  --}}
        <div class="col-md-6">
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Shift</label>
            <div class="col-sm-9">
              <select class="form-control" name="shift" >
                <option value="Ca 1">Ca 1: 8:30 - 17:30</option>
                <option value="Ca 2">Ca 2: 8:00 - 17:00</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        {{-- Nhập hệ số lương --}}
        <div class="col-md-6">
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Coefficients Salary</label>
            <div class="col-sm-9">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text bg-primary text-white">$</span>
                </div>
                <input type="text" class="form-control" name="coefficients_salary" value="0" value="{{old('coefficients_salary')}}" aria-label="Amount (to the nearest dollar)"/>
                <div class="input-group-append">
                  <span class="input-group-text">vnđ</span>
                </div>
              </div>
              
            </div>
          </div>
        </div>
      </div>
      
      <div class="row ml-2">
        <button type="submit" class="btn btn-primary" style="margin-right: 10px">Submit</button>
        <a href="{{route('staff.index')}}" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
</div>
</div>
</div>
</div>
@endsection

