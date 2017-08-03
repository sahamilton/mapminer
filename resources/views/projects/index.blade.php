@extends('admin.layouts.default')
@section('content')

<h2>All Indiana Construction Projects</h2>

<p><a href="{{route('projects.map')}}">
<i class="glyphicon glyphicon-flag"> </i>Map view</a></p>

<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
	<thead>
		
		<th>Project</th>
		<th>Address</th>
		<th>City</th>
		<th>State</th>
		<th>Type</th>
		<th>Ownership</th>
		<th>Stage</th>
		
		<th>Total Value ($k)</th>

	</thead>
	<tbody>
	@foreach($projects as $project)
		<tr>  
		<td><a href="{{route('projects.show',$project->id)}}"
		title="See details of this project">{{$project->project_title}}</a></td>
		<td>{{$project->project_addr1}}</td>
		<td>{{$project->project_city}}</td>
		<td>{{$project->project_state}}</td>
		<td>{{$project->structure_header}} / {{$project->project_type}}</td>
		<td>{{$project->ownership}}</td>
		<td>{{$project->stage}}</td>
		<td style="text-align:right">{{$project->total_project_value}}</td>
		</tr>
	@endforeach

	</tbody>
</table>
@include('partials._modal')
@include('partials._scripts')
@stop