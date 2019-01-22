<p><span style="color:red">*</span> Includes the following roles:

		@foreach ( $roles as $role)
			{{$role->displayName}}
			 @if (! $loop->last)
			 ,
			 @endif
		@endforeach

</p>
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
<thead>
	<th>Manager</th>
	<th>Roles</th>
	<th>Serviceline</th>
	<th>Employee Id</th>
	<th>Email</th>
	<th>Reports To</th>
</thead>
	<tbody>
		@foreach ($people as $manager)

		<tr>
			<td><a href="{{route('person.details',$manager->id)}}">{{$manager->fullName()}}</a></td>
			<td>
				
					<ul style=" list-style-type: none;">
					@foreach ( $manager->userdetails->roles as $role)
						<li>{{$role->displayName}}</li>
					@endforeach
					</ul>

				
			</td>
			<td>
				@if($manager->userdetails->serviceline)
					<ul style=" list-style-type: none;">
					@foreach ( $manager->userdetails->serviceline as $serviceline)
						<li>{{$serviceline->ServiceLine}}</li>
					@endforeach
					</ul>

				@endif
			</td>
			<td>{{$manager->userdetails->employee_id}}</td>
			<td>{{$manager->userdetails->email}}</td>
			<td>@if($manager->reportsTo){{$manager->reportsTo->fullName()}}@endif</td>
		</tr>
		@endforeach
	</tbody>
</table>