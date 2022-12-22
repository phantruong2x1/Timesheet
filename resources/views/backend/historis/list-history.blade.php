@extends('backend.layouts.master')
@section('title')
    {{ $title }}
@endsection

@section('content')
<div class="main-panel">
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                        <p class="card-title mb-0">History</p>
                    <div class="table-responsive">
                        <table class="table table-striped table-borderless">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Record Type</th>
                                    <th>Record ID</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($historyList))
                                @foreach ($historyList as $key=>$item)
                                <tr>
                    
                                    {{-- Hiển thị dữ liệu --}}
                                    <td>{{$key+1}}</td>
                                    <td>{{$item->staff_id}}</td>
                                    <td>{{date('d-m-Y', $item->time/1000)}}</td>
                                    <td>{{date('H:i:s', $item->time/1000)}}</td>

                                    {{-- record type --}}
                                    @if($item->record_type == 31)
                                        <td>The door is open</td>
                                    @elseif($item->record_type == 30)
                                        <td>The door is closed</td>
                                    @elseif($item->record_type == 8)
                                        <td>Unlock with fingerprint</td>
                                    @else
                                        <td>Unknown</td>
                                    @endif

                                    <td>{{$item->record_id}}</td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="6">There is no data!</td>
                                </tr>
                              @endif
                            </tbody>                            
                        </table>                       
                    </div>                   
                </div>
            </div>
        </div>  
        {{ $historyList->links() }}
    </div>  
</div> 
</div>

@endsection


