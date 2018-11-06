@extends('admin.layouts.default')
@section('content')
<div class="container">
	<h2>Communication Campaign</h2>
	<p><a href="{{route('campaigns.index')}}">Return to all campaigns</a></p>
	<h4>{{ucwords($campaign->type)}}</h4>
	<p><strong>Created By:</strong>{{$campaign->author ? $campaign->author->fullName() :''}}</p>
	<p><strong>Sent:</strong>{{$campaign->created_at->format('l jS M Y')}}</p>
	<p><strong>Expires:</strong>{{$campaign->expiration ? $campaign->expiration->format('l jS M Y') .' at ' .$campaign->expiration->format('g:i a') : ''}}</p>
	<p><strong>Message:</strong>{!!$campaign->message!!}</p>

	<h4>Participants</h4>
	<table class="table" id="sorttable">
		<thead>
			<th>Name</th>
			<th>Status</th>
			
		</thead>
		<tbody>
			@foreach ($campaign->participants as $participant)
			
			<tr>
				<td>
					<a href="{{route('person.details',$participant->id)}}">{{$participant->fullName()}}
					</a>
				</td>
				<td>{{$participant->pivot->activity}}</td>
			</tr>
			@endforeach
		</tbody>
	</table>


@include ('partials._scripts')
@endsection()