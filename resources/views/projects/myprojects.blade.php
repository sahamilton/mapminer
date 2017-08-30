@extends('site.layouts.default')
@section('content')
<h2>My Projects</h2>
<p><a href="{{route('projects.index')}}">Return to all projects</a></p>
<p><a href="{{route('projects.export')}}" title="Download my claimed projects as a CSV / Excel file"><i class="glyphicon glyphicon-cloud-download"></i> Download My Projects</a>
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
	<thead>
		
		<th>Project</th>
		<th>Address</th>
		<th>City</th>
		<th>State</th>
		<th>Type</th>
		<th>Ownership</th>
		<th>Stage</th>
		<th>PR Status</th>
		
		<th>Total Value ($k)</th>
		<th>Rating</th>

	</thead>
	<tbody>
	@foreach($projects as $project)
		
		<tr>  
		<td><a href="{{route('projects.show',$project->id)}}"
		title="See details of this project">{{$project->project_title}}</a></td>
		<td>{{$project->project_addr1}}</td>
		<td>{{$project->project_city}}</td>
		<td>{{$project->project_state}},{{$project->project_zipcode}}</td>
		<td>{{$project->structure_header}} / {{$project->project_type}}</td>
		<td>{{$project->ownership}}</td>
		<td>{{$project->stage}}</td>
		<td>
		@foreach ($project->owner as $owner)
		{{$owner->pivot->status}}
		@endforeach
		</td>
		<td style="text-align:right">{{$project->total_project_value}}</td>
		<td>
		@foreach ($project->owner as $owner)
		{{$owner->pivot->ranking}}
		
		@endforeach
		</td>
		</tr>
	@endforeach

	</tbody>
</table>
@include('partials._scripts')

@endsection
