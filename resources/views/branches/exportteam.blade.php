
<table>
	<tbody>
		<tr>

			<td>Branch Number</td>
			<td>Branch Name</td>
			<td>City</td>
			<td>State</td>
			<td>Country</td>
			<td>Manager</td>
			<td>Reports To</td>
			<td>Role</td>
	
			
		</tr>
		@foreach($result as $branch)
			
			<tr>  

				<td>{{$branch->id}}</td>
				<td>{{$branch->branchname}}</td>
				<td>{{$branch->city}}</td>
				<td>{{$branch->state}}</td>
				<td>{{$branch->country}}</td>
				<td>
					@foreach($branch->manager as $person)
	    				{{$person->fullName()}}<br /> 
					@endforeach
				</td>
				<td>
					
					@foreach($branch->manager as $manager)
						{{$manager->reportsTo ? $manager->reportsTo->fullName() : 'No Reporting manager'}}<br />
						
					@endforeach
	
				</td>
				<td>
					@foreach($branch->manager as $manager)
						{{$manager->reportsTo ? implode(",",$manager->reportsTo->userdetails->roles->pluck('display_name')->toArray()) : ''}}<br />
						
					@endforeach
				</td>
				
				

			</tr>
		@endforeach
	</tbody>
</table>
