<table>
	<tbody>
		<tr>
			<td>id</td>
			<td>firstname</td>
			<td>lastname</td>
			<td>Username</td>
			<td>Employee Number</td>
			<td>Email</td>
			<td>Created</td>
			<td>Serviceline</td>
			<td>Roles</td>
		</tr>
		@foreach($data as $person)
		<tr>  
			<td>{{$person->id}}</td>
			<td>{{$person->firstname}}</td>
			<td>{{$person->lastname}}</td>
			<td>{{$person->userdetails->username}}</td>
			<td>{{$person->userdetails->employee_id}}</td>
			<td>{{$person->userdetails->email}}</td>
			<td>{{$person->userdetails->created_at->format('m/d/Y')}}</td>
			<td>
			@foreach ($person->userdetails->serviceline as $serviceline)
				{{$serviceline->ServiceLine}}
				@if(! $loop->last)
				|
				@endif
			@endforeach
			</td>
			<td>
			@foreach ($person->userdetails->roles as $role)
				{{$role->name}}
				@if(! $loop->last)
				|
				@endif
			@endforeach
			</td>
		</tr>
		@endforeach
	</tbody>
</table>