@extends('admin.layouts.default')
@section('content')

<h4>Users Not in Import</h4>

<form name="deleteusers" method="post" action="{{route('user.importdelete')}}">
	@csrf
	<table class="table" id ="nosorttable">
		<thead>
			<th><input type="checkbox" checked id="checkAll"></th>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Email</th>
			<th>Employee ID</th>
			<th>Roles</th>
		
		</thead>
		<tbody>
			@foreach($missingPeople as $user)

				<tr>
					<td><input type="checkbox" name="delete[]" value="{{$user->id}}" checked /></td>
					<td>{{$user->person->firstname}}</td>
					<td>{{$user->person->lastname}}</td>
					<td>{{$user->email}}</td>
					<td>{{$user->employee_id}}</td>
					<td>
						@foreach ($user->roles as $role)
							<li>{{$role->display_name}}</li>
						@endforeach
					</td>
					

				</tr>
			@endforeach
		</tbody>
	</table>
	<input type="submit" name="submit" value="Delete Checked" class="btn btn-danger" />
</form>
@include('partials._scripts')

@endsection