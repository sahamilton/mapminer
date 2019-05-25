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

<div class="col-sm-10 offset-1">
	<table class='table table-striped table-bordered table-condensed table-hover sorttable'>

		<thead style="background-color:#E77C22;  color:#fff;">
			<th colspan=6 class="text-center">Summary</th>
			<tr>
				<th class="text-center">Top 50 Open Opportunities</th>
				<th class="text-center">All Open Opportunities</th>
				<th class="text-center">Won</th>
				<th class="text-center">Lost</th>
				<th class="text-center">Leads</th>
				<th class="text-center">Activities</th>
			</tr>
		</thead>
		<tbody>
			<td class="text-center">
				<a href="{{route('opportunity.index')}}">{{$data['summary']->first()->top50}}</a></td>
			<td class="text-center">{{$data['summary']->first()->open}}</td>
			<td class="text-center">{{$data['summary']->first()->won}}</td>
			<td class="text-center">{{$data['summary']->first()->lost}}</td>
			<td class="text-center">
				<a href="{{route('branch.leads')}}">{{$data['summary']->first()->leads_count}}</a></td>
			<td class="text-center">
				<a href="{{route('activity.index')}}">{{$data['summary']->first()->activities_count}}</a></td>	
		</tbody>
	</table>
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
			<canvas id="ctpipe" width="450" height="400" ></canvas>
			@include('charts._pipechart')
		</div>
	</div>
	<div class="card float-right" style="margin-top:10px">
		<div class="card-header">
			<h4>Activities</h4>
		</div>
		<div class="card-body">
				  <canvas id="ctb" width="450" height="400" ></canvas>
				@include('charts._branchactivitiestype')
		</div>
	</div>


@include('partials._scripts')
@endsection
