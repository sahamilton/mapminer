
<table>
	<tbody>
		<tr>

			<td>Branch Number</td>

			<td>Branch Name</td>
			<td>Team Members</td>
			<td>Employee Id</td>
			<td>Role Id</td>
			<td>Role</td>
			
		</tr>
		@foreach($result as $branch)
			<tr>  

				<td>{{$branch->id}}</td>
				<td>{{$branch->branchname}}</td>
				@foreach($branch->relatedPeople as $team)
					<td>{{$team->fullName()}}</td>
					<td>{{$team->userdetails->employee_id}}</td>
					<td>{{$team->pivot->role_id}}</td>
					@if(isset($roles[$team->pivot->role_id]))
					<td>{{$roles[$team->pivot->role_id]}}</td>
					@endif
				@endforeach
			</td>
				
				

			</tr>
		@endforeach
	</tbody>
</table>