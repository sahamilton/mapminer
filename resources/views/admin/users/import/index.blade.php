@extends('admin.layouts.default')
@section('content')

<h4>Import Items Not Completed</h4>
<table class="table">
	<thead>
		<th>First Name</th>
		<th>Last Name</th>
		<th>Employee ID</th>
		<th>Manager</th>
		<th>Manager Employee Id</th>
	</thead>
	<tbody>
		@foreach($imports as $person)
			<tr>
				<td>{{$person->firstname}}</td>
				<td>{{$person->firstname}}</td>
				<td>{{$person->employee_id}}</td>
				<td>{{$person->manager}}</td>
				<td>{{$person->mgr_emp_id}}</td>
			</tr>
		@endforeach
	</tbody>
</table>

@endsection