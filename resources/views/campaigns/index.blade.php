@extends('admin.layouts.default')
@section('content')
<div class="container">
	<h2>Branch Sales Campaigns</h2>


	<div class="float-right">
   		<a href="{{route('campaigns.create')}}" class="btn btn-info">Create New Campaign</a>
   </div>
<ul class="nav nav-tabs">
        <li class="nav-item ">
            <a class="nav-link active"  data-toggle="tab" href="#calendar">Calendar</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#list">List</a>
        </li>

    </ul>
    
    <div class="tab-content">
        <div id="calendar" class="tab-pane fade show active">

            <div class='col-md-offset-2 col-md-8' style="margin-top:20px">
                {!! $calendar->calendar() !!}
                {!! $calendar->script() !!}
            </div>  
        </div>
        <div id="list" class="tab-pane fade  ">      
        @include('campaigns.partials._list')

        </div>
    </div>

@include('partials._modal')
@include ('partials._scripts')
@endsection()