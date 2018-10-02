@extends ('admin.layouts.default')
@section('content')
<div class="container">
    <h2>Sales Campaigns</h2>
    <div class="pull-right">
        <a href ="{{route('salesactivity.create')}}">
            <button class="btn btn-success" ><i class="far fa-briefcase" aria-hidden="true"> </i> Add Sales Campaign</button>
        </a>
    </div> 
    <ul class="nav nav-tabs">
        <li class="nav-item active">
            <a class="nav-link active"  data-toggle="tab" href="#calendar">Calendar</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#list">List</a>
        </li>
    </ul>

    <div class="tab-content">
        <div id="calendar" class="tab-pane fade in active">

            <div class='col-md-offset-2 col-md-8' style="margin-top:20px">
                {!! $calendar->calendar() !!}
                {!! $calendar->script() !!}
            </div>  
        </div>
        <div id="list" class="tab-pane fade in ">      
        @include('salesactivity.partials._tablist')

        </div>
    </div>
@include('partials._scripts')
@endsection
