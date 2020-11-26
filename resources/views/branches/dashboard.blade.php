@extends('site.layouts.cal')
@section('content')
@include('partials._newsflash')

<div class="container" style="margin-bottom:100px">

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

<div class="col-sm-12">
	<table id ='responsive6'  class="display responsive no-wrap" width="100%">

		<thead style="background-color:#E77C22;  color:#fff;">
			<th colspan=6 class="text-center">Summary</th>
			<tr>
				<th class="text-center">All Open Opportunities</th>
				<th class="text-center">Top 25 Open Opportunities</th>
				
				<th class="text-center">Won</th>
				<th class="text-center">Lost</th>
				<th class="text-center">Active Leads</th>
				<th class="text-center">Period Activities</th>
			</tr>
		</thead>
		<tbody>
			<td class="text-center">
				<a href="{{route('opportunity.index')}}">
					{{$data['summary']->first()->open_opportunities }}</a></td>
			<td class="text-center">{{$data['summary']->first()->top25_opportunities}}</td>
			<td class="text-center">{{$data['summary']->first()->won_opportunities}}</td>
			<td class="text-center">{{ $data['summary']->first()->lost_opportunities}}</td>
			<td class="text-center">
				<a href="{{route('branch.leads')}}">{{$data['summary']->first()->active_leads}}</a></td>
			<td class="text-center">
				<a href="{{route('activity.index')}}">{{$data['summary']->first()->activities_count}}</a></td>	
		</tbody>
	</table>
</div>

	<div class="col-sm-10 offset-1">
		<div class="card-header">
			<h4>Activities Calendar</h4>
			<p><a href="{{route('upcomingactivity.branch',$branch->id)}}">Upcoming Activities</a></p>
		</div>

		<div id="calendar"  class="card-body" ></div>
	</div>
	 
	<div class="row" style="margin-bottom:100px">
	<div class="col-sm-6 float-left" style="margin-top:10px">
		<div class="card-header">
			<h4>Sales Pipeline</h4>
		</div>
		<div class="card-body">
			<canvas id="ctpipe" width="450" height="400" ></canvas>
			@include('charts._pipechart')
		</div>
	</div>
	<div class="col-sm-6 float-right" style="margin-top:10px">
		<div class="card-header">
			<h4>Activities</h4>
		</div>@if(count($data['activitychart']) >0)
		<div class="card-body">
				  <canvas id="ctb" width="450" height="400" ></canvas>
				 
					@include('charts._branchactivitiestype')

		</div>
		@else
			<p class="text-warning">No Activities in this period</p>
		@endif
	</div>
</div>
</div>
@include('partials._scripts')
@include('partials._calendarscript')

@endsection
