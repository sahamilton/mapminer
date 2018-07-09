
<table>
	<tbody>
		<tr>
			<td>Project Name</td>
			<td>Street</td>
			<td>Address</td>
			<td>City</td>
			<td>State</td>
			<td>ZIP</td>
			<td>Dodge Ref #</td>
			<td>Type</td>
			<td>Project</td>
			<td>Stage</td>
			<td>Ownership</td>
			<td>Bid Date</td>
			<td>Start Year / Month</td>
			<td>Target Start Date}}</td>
			<td>Target Comp Date</td>
			<td>Work Type</td>
			<td>Status</td>
			<td>Total Project Value $k</td>
			<td>PR Status</td>
			<td>Owned By</td>
			
		@foreach($projects as $project)
			<tr> 
			<td>{{$project->project_title}}</td>
			<td>{{$project->address->street}}</td>
			<td>{{$project->addr2}}</td>
			<td>{{$project->address->city}}</td>
			<td>{{$project->address->state}}</td>
			<td>{{$project->address->zipcode}}</td>
			<td>{{$project->dodge_repnum}}</td>
			<td>{{$project->structure_header}}</td>
			<td>{{$project->project_type}}</td>
			<td>{{$project->stage}}</td>
			<td>{{$project->ownership}}</td>
			<td>{{$project->bid_date}}</td>
			<td>{{$project->start_yearmo}}</td>
			<td>{{$project->target_start_date}}</td>
			<td>{{$project->target_comp_date}}</td>
			<td>{{$project->work_type}}</td>
			<td>{{$project->status}}</td>
			<td>{{$project->total_project_value}}k</td>
			<td>
			@foreach ($project->owner as $owner)
				
					{{$owner->pivot->status}}
				</td><td>
					{{$owner->postName()}}
			
			</td>
			@endforeach
				
				

			</tr>
		@endforeach
	</tbody>
</table>