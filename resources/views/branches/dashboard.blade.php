@extends('site.layouts.cal')
@section('content')
@include('partials._newsflash')

<div class="container" style="margin-bottom:100px">

<h2>{{$branch->branchname}} Dashboard</h2>

@include('branches.partials._periodselector')
<div class="m-2">
	@foreach ($branch->manager as $manager)
		<p><strong>Manager:</strong>{{$manager->fullName()}}</p>
		@foreach ($manager->directReports as $teammember)
			<li>{{$teammember->fullName()}}</li>
		@endforeach
	@endforeach
</div>
@if(isset($data['mybranches']) && $data['mybranches']->count() >1)
	
	@include('dashboards.partials._branchnewdashboardselector')

@endif
@if($data['team'])

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
					{{$data['teamdata']->first()['open_opportunities'] }}
				</a>
				</td>
			<td class="text-center">{{$data['teamdata']->sum('top25_opportunities')}}
			</td>
			<td class="text-center">{{$data['teamdata']->sum('won_opportunities')}}
			</td>
			<td class="text-center">{{ $data['teamdata']->sum('lost_opportunities')}}
			</td>
			<td class="text-center">
				<a href="{{route('branch.leads')}}">{{$data['teamdata']->sum('leads')}}
				</a>
			</td>
			
			<td class="text-center">
				<a href="{{route('activity.branch', $branch->id)}}">
				{{$data['teamdata']->sum('activities_count')}}
			</a>
				
	
			</td>	
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
		@if($data['team']->count()>1)
			<div style="width: 40%;float:left;border:solid 1px #aaaaaa;margin:5px;margin-left:5px">
				<div class="card-header">
					<h4>Team Activities</h4>
				</div>
				<div class="card-body">
					<canvas id="ctp" width="300" height="300" style="float-right"></canvas>
					@include('charts._personactivitiesstackedchart')
				</div>

			</div>
		@endif
		@ray($data['charts'])
		
		<div style="width: 40%;float:right;border:solid 1px #aaaaaa;margin:5px;margin-left:5px">
			<div class="card-header">
				<h4>Branch Activities</h4>

			</div>
			
			<div class="card-body">
					  
					 	@if(count($data['charts']['activitychart']))
					 	<canvas id="ctb" width="300" height="300" style="float-right"></canvas>
						@include('charts._branchactivitiestype')
						@else
							No Activities in this time period
						@endif

			</div>
		</div>
		
	</div>
</div>
@include('partials._scripts')
@include('partials._calendarscript')

@endsection
