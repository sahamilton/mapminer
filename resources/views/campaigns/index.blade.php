@extends('site.layouts.default')
@section('content')
<div class="container">
	<h2>Communication Campaigns</h2>
	<table class="table" id="sorttable">
		<thead>
			<th>Created</th>
			<th>Author</th>
			<th>Type</th>
			<th>Participants</th>
			<th>Respondents</th>
			<th>Is Test</th>
			<th></th>
		</thead>
		<tbody>
			@foreach ($campaigns as $campaign)
			
			<tr>
				<td><a href="{{route('campaigns.show',$campaign->id)}}"
					title="See details of this campaign">{{$campaign->created_at->format('Y-m-d')}}</a></td>
				<td>@if($campaign->author) {{$campaign->author->fullName()}} @endif</td>
				<td><a href="{{route($campaign->route)}}">{{ucwords($campaign->type)}}</a></td>
				<td>{{$campaign->participants->count()}}</td>
				<td>{{$campaign->respondents->count()}}</td>
				<td>
					@if($campaign->test == 'null')
						Yes
					@else
						No
					@endif
				</td>
				<td>
					<a 
					 	data-href="{{route('campaigns.destroy',$campaign->id)}}" 
						data-toggle="modal" 
						data-target="#confirm-delete" 
						data-title = "campaign"
						title ="Delete this campaign" 
						href="#">

						<i class="far fa-trash-alt text-danger" aria-hidden="true"> </i> </a>
			</tr>
			@endforeach
		</tbody>
	</table>

@include('partials._modal')
@include ('partials._scripts')
@endsection()