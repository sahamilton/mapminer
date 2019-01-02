<form name="fiximporterrors" method="post" action="{{route('fixusercreateerrors')}}" >
		@csrf
		<table class="table">
			<thead>
				<th>Import Person</th>
				<th>Import Email</th>
				<th>Import Employee Id</th>
				<th>Existing Email</th>
				<th>Existing Employee Id</th>
				
			</thead>
			<tbody>
				@foreach ($data['errors']['emails'] as $person)
				<tr>
					<td>{{$person->firstname}} {{$person->lastname}}</td>
					<td>{{$person->email}}</td>
					<td>{{$person->employee_id}}
						<input type="radio" name="import[{{$person->id}}]" value="import"  />
					</td>
					<td>{{$person->useremail}}</td>
					<td>
						<input type="radio" name="import[{{$person->id}}]" value="existing" checked />{{$person->userempid}}
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		<input type="hidden" name="type" value="email" />
		<input class="btn btn-success" name="submit" type="submit" value="update import errors" >
	</form>
