@extends('site/layouts/default')
@section('content')

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

<div id="map" style="height:300px;width:500px;border:red solid 1px;" ></div>
<div style="clear:both"></div>
	@include('webleads.partials.map')	</div>
	<div class="row">
	@include('leads.partials._branchlist')
</div>
<div class="row">
	@include('leads.partials._repslist')
</div>
	<!--

		map

		Closets Branches

		Closest sales reps
	-->

@include('partials/_scripts')
@stop

