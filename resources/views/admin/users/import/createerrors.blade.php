@extends('admin.layouts.default')

@section('content')
<div class="container">
	<h4>Import Errors</h4>
	<div class="alert alert-warning">
		<p>Fix these errors and reimport</p>
		
	</div>
	<form name="fixusercreateerrors" method="post" action="{{route('fixusercreateerrors')}}" >
		@csrf
		<table class="table">
			<thead>
				<th>Person</th>
				<th>Employee Id</th>
				<th>Email</th>
				<th></th>
				
				<th></th>
			</thead>
			<tbody>
				@foreach ($persons as $person)
				<tr>
					<td>{{$person->firstname}} {{$person->lastname}}</td>
					<td>{{$person->employee_id}}</td>
					<td><input type="text" name="email[{{$person->employee_id}}]" value="{{$person->email}}" >
					</td>
					<td class="text text-danger">
						@if(array_key_exists($person->employee_id,$importerrors['email']))
						
							<i class="fas fa-exclamation-triangle text text-danger"
							title="{{$person->email}} is not unique."
							></i>
						
						@endif
					</td>
					
				</tr>
				@endforeach
			</tbody>
		</table>
		<input class="btn btn-success" name="submit" type="submit" value="update import errors" >
	</form>
</div>

@endsection