@extends('site/layouts/default')
@section('content')
<div class="col-sm-5">
	<div class="panel panel-default">
		<div class="panel-heading clearfix">
			<h2 class="panel-title pull-left">{{$lead->company_name}} - {{$lead->rating}}</h2>
			<a class="btn btn-primary pull-right" href="{{route('webleads.edit',$lead->id)}}">
				<i class="fa fa-pencil"></i>
				Edit
			</a>
		</div>
		<div class="list-group-item">
			<p class="list-group-item-text">Lead Details</p>
			<ul style="list-style-type: none;">
				<li><strong>Address:</strong>{{$lead->city}}, {{$lead->state}}</li>
				<li><strong>Contact:</strong>{{$lead->first_name}} {{$lead->last_name}}</li>
				<li><strong>Phone:</strong>{{$lead->phone_number}}</li>
				<li><strong>Email:</strong>{{$lead->email_address}}</li>
				
			
			</ul>
		</div>

		<div class="list-group">
			<div class="list-group-item">
				<p class="list-group-item-text">Job Requirements</p>
				<ul style="list-style-type: none;">
						<li><strong>Time Frame:</strong>{{$lead->time_frame}}</li>
						<li><strong>Jobs:</strong>{{$lead->jobs}}</li>
						<li><strong>Industry:</strong>{{$lead->industry}}</li>
				</ul>
			</div>
		</div>

		@if(count($lead->salesteam)>0)
		<div class="list-group">
			<div class="list-group-item">
				<p class="list-group-item-text">Lead Assigned</p>
				<ul style="list-style-type: none;">
						<li><strong>Lead Assigned to:</strong>{{$lead->salesteam->first()->postName()}}</li>
						<li><strong>Lead Assigned on:</strong>{{$lead->salesteam->first()->pivot->created_at->format('j M, Y')}}</li>
						
				</ul>
			</div>
		</div>
		@endif
	</div>
@if(count($lead->salesteam)==0)
		@include('leads.partials._branchlist')	
			
		
@endif
</div>		<div id="map" class="col-sm-7 pull-right" style="border:solid 1px red"></div>
@if(count($lead->salesteam)==0)
		<div class="row">
		<div class="col-sm-12">
		@include('leads.partials._repslist')
		</div>
@endif
</div>
	<!--

		map

		Closets Branches

		Closest sales reps
	-->
@include('webleads.partials.map')	
@include('partials/_scripts')
@stop

