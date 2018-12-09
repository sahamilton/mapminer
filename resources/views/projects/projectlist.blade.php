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
		<th>Distance</th>
		

	</thead>
	<tbody>
	@foreach($data['result'] as $project)

		<tr>  
		<td><a href="{{route('projects.show',$project->id)}}"
		title="See details of this project">{{$project->project_title}}</a></td>
		<td>{{$project->street}}</td>
		<td>{{$project->city}}</td>
		<td>{{$project->state}},{{$project->zip}}</td>
		<td>{{$project->structure_header}} / {{$project->project_type}}</td>
		<td>{{$project->ownership}}</td>
		<td>{{$project->stage}}</td>
		<td>
		
		@if($project->owner->count()>0 && $project->owner[0]->id != auth()->user()->person()->first()->id)
			Project {{$project->owner[0]->pivot->status}} by {{$project->owner[0]->postName()}}
		@elseif($project->owner->count()>0 && $project->owner[0]->id == auth()->user()->person()->first()->id)
			You have {{$project->owner[0]->pivot->status}} this project
		@else
  			@can ('manage_projects')
  				Open <a href="{{route('projects.claim',$project->id)}}">Claim this project </a>
  			@endcan
		@endif
		
		</td>
		<td style="text-align:right">{{$project->total_project_value}}</td>
		<td>{{number_format($project->distance,1)}}</td>
		
		</tr>
	@endforeach

	</tbody>
</table>
