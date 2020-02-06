<table>
<tbody>
	<tr>
	<th>Manager</th>
	<th>Roles</th>
	<th>Serviceline</th>
	<th>Employee Id</th>
	<th>Email</th>
	<th>Reports To</th>
	</tr>
	
		@foreach ($people as $manager)

		<tr>
			<td><a href="{{route('person.details',$manager->id)}}">{{$manager->fullName()}}</a></td>
			<td>
					@foreach ( $manager->userdetails->roles as $role)
					@if(! $loop->first),@endif
						{{$role->display_name}}
					@endforeach
				
			</td>
			<td>
				@if($manager->userdetails->serviceline)
					
					@foreach ( $manager->userdetails->serviceline as $serviceline)
						@if(! $loop->first),@endif
						{{$serviceline->ServiceLine}}
					@endforeach
					

				@endif
			</td>
			<td>{{$manager->userdetails->employee_id}}</td>
			<td>{{$manager->userdetails->email}}</td>
			<td>@if($manager->reportsTo){{$manager->reportsTo->fullName()}}@endif</td>
		</tr>
		@endforeach
	</tbody>
</table>