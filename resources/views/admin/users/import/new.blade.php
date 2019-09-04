@extends('admin.layouts.default')
@section('content')

<h4>New Users to Create</h4>
<form name="createnewusers" method="post" action = "{{route('user.importinsert')}}" >
	@csrf
<table class="table table-striped table-bordered" id ="nosorttable">
	<thead>
		<th><input type="checkbox" checked id="checkAll"></th>
		<th>First Name</th>
		<th>Last Name</th>
		<th>Employee ID</th>
		<th>Business Title</th>
		<th>Role</th>
		<th>Manager</th>
		<th>Manager Employee Id</th>
	</thead>
	<tbody>
		@foreach($newPeople as $person)
			<tr>
				<td><input type="checkbox" name="insert[]" value="{{$person->id}}" checked /></td>
				<td>{{$person->firstname}}</td>
				<td>{{$person->lastname}}</td>
				<td>{{$person->employee_id}}</td>
				<td>{{$person->business_title}}</td>
				<td>
					<select name="role[{{$person->id}}]" >
						@foreach ($roles as $role)
							<option value="{{$role->id}}">{{$role->display_name}}</option>
						@endforeach
					</select>
				</td>
				<td>{{$person->manager}}</td>
				<td>{{$person->reports_to}}</td>
			</tr>
		@endforeach
	</tbody>
</table>
	<input type="submit" name="submit" value="Create Checked" class="btn btn-danger" />
</form>
@include('partials._scripts')
@endsection