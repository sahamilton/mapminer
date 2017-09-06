@extends('admin.layouts.default')
@section('content')
<h2>Owned Projects</h2>
<p><a href="{{route('project.stats')}}">See projects summary</a></p>
<p><a href="{{route('projects.exportowned')}}" title="Download my claimed projects as a CSV / Excel file"><i class="glyphicon glyphicon-cloud-download"></i> Download Owned Projects</a>
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
	<thead>
		
		<th>Project</th>
		<th>Source</th>
		<th>Address</th>
		<th>City</th>
		<th>State</th>
		<th>Type</th>
		<th>Ownership</th>
		<th>Stage</th>
		<th>PR Status</th>
		<th>Owned By</th>
		<th>Ranking</th>
		<th>Total Value ($k)</th>

	</thead>
	<tbody>
	@foreach($projects as $project)
		
		<tr>  
		<td><a href="{{route('projects.show',$project->id)}}"
		title="See details of this project">{{$project->project_title}}</a></td>
		<td><a href="{{route('project.stats'). "?id=". $project->source->id}}">{{$project->source->source}}</a></td>
		<td>{{$project->project_addr1}}</td>
		<td>{{$project->project_city}}</td>
		<td>{{$project->project_state}},{{$project->project_zipcode}}</td>
		<td>{{$project->structure_header}} / {{$project->project_type}}</td>
		<td>{{$project->ownership}}</td>
		<td>{{$project->stage}}</td>
		<td>
		@foreach ($project->owner as $owner)
		{{$owner->pivot->status}}
		</td><td>
		{{$owner->postName()}}
		</td><td>
		{{$owner->pivot->ranking}}
		@endforeach
		</td>
		<td style="text-align:right">{{$project->total_project_value}}</td>
		</tr>
	@endforeach

	</tbody>
</table>
@include('partials._scripts')
@endsection
