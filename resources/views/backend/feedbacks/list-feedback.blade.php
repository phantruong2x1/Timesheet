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
                        @if(!empty($listFeedback))
                        @foreach ($listFeedback as $key=>$item)
                            <div class="media border p-3 m-3 rounded shadow">
                                <div class="media-body">
                                    <p style="color: rgb(128, 128, 128)" class="d-flex justify-content-center">{{$item->created_at}}</p>
                                    <h4>{{$item->staff->full_name}} <small><i>{{$item->title}}</i></small></h4>
                                    
                                    
                                    <p>{{$item->content}}</p>      
                                </div>
                            </div>
                        @endforeach
                        @else
                        <div class="alert alert-primary">
                             There is no data!
                        </div>
                        @endif
                                    
                    </div>                   
                </div>
            </div>
        </div>
        {{ $listFeedback->links() }}  
    </div>  
</div> 
</div>

@endsection


