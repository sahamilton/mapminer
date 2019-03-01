@extends('site.layouts.default')
@section('content')
<div class="container" style="clear:both">
<h2>{{$data['branch']->first()->branchname}} Dashboard</h2>
	<div class="col-sm-3 float-left">
		<div class="card">
			<div class="card-header">
				<h4>Summary</h4>
			</div>
			<div class="card-body">
				<p><strong>Leads:</strong><a href="{{route('lead.branch',$data['branch']->first()->id)}}">{{$data['branch']->first()->leads_count}}</a></p>
				<p><strong>Opportunities:</strong><a href="{{route('opportunities.branch',$data['branch']->first()->id)}}">{{$data['branch']->first()->opportunities_count}}</a></p>
				<p><strong>Won:</strong>{{$data['branch']->first()->won}}</p>
				<p><strong>Lost:</strong>{{$data['branch']->first()->lost}}</p>
				<p><strong>Activities:</strong><a href="{{route('activity.branch',$data['branch']->first()->id)}}">{{$data['branch']->first()->activities_count}}</a></p>
			</div> 
			<div class="card-footer"></div>
			</div>
	</div>
	<div class="float-left">
		<div class="card">
			<div class="card-header">
				<h4>Weekly Activity</h4>
			</div>
			<div class="card-body">
				  <canvas id="ctx" width="300" height="400" ></canvas>
				@include('activities.partials._chart')
			</div>
		</div>
	</div>
	<div class="float-left">
		<div class="card">
			<div class="card-header">
				<h4>Sales Funnel</h4>
			</div>
			<div class="card-body">
				@include('branches.partials._funnel')
			</div>
		</div>
	</div>
</div>
@include('branches.partials._upcoming')
<div class="container">

</div>
@include('partials._scripts')
@endsection
