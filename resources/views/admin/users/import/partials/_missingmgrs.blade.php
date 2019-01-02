<h4>Missing Managers</h4>

<form name="createnewusers" method="post" action = "{{route('user.importinsert')}}" >
	@csrf
<table class="table" id ="sorttable1">
	<thead>
		<th><input type="checkbox" checked id="checkAll2"></th>
		<th>First Name</th>
		<th>Last Name</th>
		<th>Employee ID</th>
		<th>Role</th>
		<th>Manager</th>
		<th>Manager Employee Id</th>
	</thead>
	<tbody>
		@foreach($data['noManagers'] as $person)
			<tr>
				<td><input type="checkbox" name="insert[]" value="{{$person->id}}" checked /></td>
				<td>{{$person->firstname}}</td>
				<td>{{$person->lastname}}</td>
				<td>{{$person->employee_id}}</td>
				<td>
					@if($person->role)
						{{$person->role->name}}
					@endif
				</td>
				<td>{{$person->manager}}</td>
				<td>{{$person->mgr_emp_id}}</td>
			</tr>
		@endforeach
	</tbody>
</table>
	<input type="submit" name="submit" value="Create Checked" class="btn btn-danger" />
</form>