
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
			<td>Company</td>
			<td>Company Role</td>
			<td>Contact</td>
			<td>Contact Role</td>
			<td>Company Street</td>
			<td>Company Address</td>
			<td>Company City</td>
			<td>Company City</td>
			<td>Company ZIP</td>
			<td>Phone</td>
		</tr>
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

			@foreach ($project->companies as $company)
				<tr>
				<?php echo str_repeat('<td> </td>',17);?>
				<td>{{$company->firm}}</td>
				<td>{{$company->pivot->type}}</td>
				
				<td>

      @if(! null==$company->employee()->first())
            {{$company->employee()->first()->contact}}
            </td>
            <td>{{$company->employee()->first()->title}}
      @else
            </td><td>
      @endif
      </td>
      <td>{{$company->addr1}}</td>
      <td>{{$company->addr2}}</td>
      <td>{{$company->city}}</td>
      <td>{{$company->state}}</td>
      <td>{{$company->zipcode}}</td>
      <td>{{$company->phone}}</td>
				</tr>
			@endforeach
				
				

			</tr>
		@endforeach
	</tbody>
</table>