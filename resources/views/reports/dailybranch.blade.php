<table>
	<thead>
		<tr></tr>
		<tr><th colspan="5">Team Mapminer Login Statistics</th></tr>
		<tr><th colspan="5">for {{$people->first()->fullName()}}</th></tr>
		<tr><th colspan="5">For the period from {{$period['from']->format('M jS,Y')}} to {{$period['to']->format('M jS,Y')}}</th></tr>
		<tr></tr>
		<tr>
			<th><b>Team Member</b></th>
			<th><b>Branches</b></th>
			<th><b>Role</b></th>
			<th><b>Logins</b></th>
			<th><b>Last Login</b></th>
		</tr>
	</thead>
	<tbody>
		@foreach ($people as $person)
			@if(! $loop->first)
				<tr>
					<td>{{$person->fullName()}}</td>
					
					<td>
						@foreach($person->branchesServiced as $branch)
							@if(!$loop->first) <br /> @endif
						{{$branch->branchname}} 
						@endforeach
					</td>
					<td>
						@foreach($person->userdetails->roles as $role)
							@if(!$loop->first) <br /> @endif
						{{$role->display_name}} 
						@endforeach
					</td>
					<td>{{$person->userdetails->usage->count()}}</td>
					<td>
						{{$person->userdetails->usage->max('lastactivity')}}
					</td>
				</tr>
			@endif
		@endforeach
	</tbody>
</table>