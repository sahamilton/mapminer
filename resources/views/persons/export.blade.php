<table>
	<tbody>
		<tr>
			<td>id</td>
			<td>firstname</td>
			<td>lastname</td>
			<td>Full Name</td>
		
			<td>Employee Number</td>
			<td>Reports To</td>
			<td>Manager id</td>
			<td>User Id</td>
			<td>Email</td>
			<td>Created</td>
			<td>Serviceline</td>
			<td>Roles</td>
			<td>Industry Focus</td>
		</tr>
		@foreach($data as $person)
		<tr>  
			<td>{{$person->id}}</td>
			<td>{{$person->firstname}}</td>
			<td>{{$person->lastname}}</td>
			<td>{{$person->firstname }} {{$person->lastname}}</td>
			
			<td>{{$person->userdetails->employee_id}}</td>
			
			@if(isset($person->reportsTo))
				<td>{{$person->reportsTo->fullname()}}</td>
				<td>{{$person->reportsTo->userdetails->employee_id}}</td>
			@else
				<td></td>
				<td></td>

			@endif
			
			
			<td>{{$person->userdetails->id}}</td>
			<td>{{$person->userdetails->email}}</td>

			<td>@if($person->userdetails->created_at)
					{{$person->userdetails->created_at->format('m/d/Y')}}</td>
				@endif
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
			<td>
			@foreach ($person->industryFocus as $industry)
				{{$industry->filter}}
				@if(! $loop->last)
				|
				@endif
			@endforeach
			</td>
		</tr>
		@endforeach
	</tbody>
</table>