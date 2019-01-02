<form name="fiximporterrors" method="post" action="{{route('fixuserinputerrors')}}" >
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
				@foreach ($data['errors'] as $person)
				<tr>
					<td>{{$person->firstname}} {{$person->lastname}}</td>
					<td>{{$person->email}}</td>
					<td>{{$person->employee_id}}</td>
					<td>{{$person->useremail}}</td>
					<td>{{$person->userempid}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		<input class="btn btn-success" name="submit" type="submit" value="update import errors" >
	</form>