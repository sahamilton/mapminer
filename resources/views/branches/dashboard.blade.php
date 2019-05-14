@extends('site.layouts.calendar')
@section('content')

<div class="container">

<h2>{{$branch->branchname}} Dashboard</h2>
@include('branches.partials._periodselector')

	@foreach ($branch->manager as $manager)
		<p><strong>Manager:</strong>{{$manager->fullName()}}</p>
	@endforeach
@if(isset($data['branches']))
	@include('branches.partials._branchdashboardselector')
@endif
@if($data['team']['team'])
	@include('opportunities.partials._dashboardselect')
@endif


	<div class="col-sm-4 float-left">
		<div class="card">
			<div class="card-header">
				<h4>Summary</h4>
			</div>
			<div class="card-body">
				<p><strong>Leads:</strong><a href="{{route('lead.branch',$branch->id)}}">{{$data['summary']->first()->leads_count}}</a></p>
				<p><strong>Top 50 Open Opportunities:</strong><a href="{{route('opportunities.branch',$branch->id)}}">{{$data['summary']->first()->top50}}</a></p>
				<p><strong>All Open Opportunities:</strong><a href="{{route('opportunities.branch',$branch->id)}}">{{$data['summary']->first()->open}}</a></p>
				<p><strong>All Won Opportunities:</strong>{{$data['summary']->first()->won}}</p>
				<p><strong>All Lost Opportunities:</strong>{{$data['summary']->first()->lost}}</p>
				<p><strong>Completed Activities:</strong><a href="{{route('activity.branch',$branch->id)}}">{{$data['summary']->first()->activities_count}}</a></p>
			</div> 
			<div class="card-footer"></div>
		</div>
		<div class="card float-left" style="margin-top:10px">
			<div class="card-header">
				<h4>Weekly Activity</h4>
			</div>
			<div class="card-body">
				  <canvas id="ctx" width="230" height="250" ></canvas>
				@include('charts._weeklyactivitychart')
			</div>
	</div>
	</div>

	<div class="card">
		<div class="card-header">
			<h4>Activities Calendar</h4>
			<p><a href="{{route('upcomingactivity.branch',$branch->id)}}">Upcoming Activities</a></p>
		</div>

		<div id="calendar"  class="card-body" >
	
			{!! $data['calendar']->calendar() !!}
			{!! $data['calendar']->script() !!}
		</div>
	</div>
	 
	
	<div class="card float-left" style="margin-top:10px">
		<div class="card-header">
			<h4>Sales Pipeline</h4>
		</div>
		<div class="card-body">
			<canvas id="ctpipe" width="400" height="400" ></canvas>
			@include('charts._pipechart')
		</div>
	</div>


@include('partials._scripts')
@endsection
