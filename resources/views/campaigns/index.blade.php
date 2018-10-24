@extends('admin.layouts.default')
@section('content')
<div class="container">
	<h2>Communication Campaigns</h2>
	<table class="table" id="sorttable">
		<thead>
			<th>Created</th>
			<th>Type</th>
			<th>Participants</th>
			<th>Respondents</th>
		</thead>
		<tbody>
			@foreach ($campaigns as $campaign)
			
			<tr>
				<td>{{$campaign->created_at->format('Y-m-d')}}</td>
				<td><a href="{{route('campaigns.show',$campaign->id)}}">{{ucwords($campaign->type)}}</a></td>
				<td>{{$campaign->participants->count()}}</td>
				<td>{{$campaign->respondents->count()}}</td>
			</tr>
			@endforeach
		</tbody>
	</table>


@include ('partials._scripts')
@endsection()