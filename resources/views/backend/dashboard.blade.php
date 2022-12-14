@extends('backend.layouts.master')

@section('title', 'Dashboard')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
    <h3 class="font-weight-bold">Welcome {{$userDetail->staff->full_name}}</h3>
    </div>
</div>
@endsection


