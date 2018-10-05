@extends('admin.layouts.default')

@section('content')
<div class = "container">
	<h4>Import Errors</h4>
	<div class="alert alert-warning">
		<p>Fix these errors and reimport</p>
	</div>
	<form name="fiximporterrors" method="post" action="{{route('fixuserinputerrors')}}" >
		@csrf
		<table class="table">
			<thead>
				<th>Person</th>
				<th>Employee Id</th>
				<th>Branches</th>
				<th>Invalid IDs</th>
			</thead>
			<tbody>
				@foreach ($persons as $person)
				<tr>
					<td>{{$person->firstname}} {{$person->lastname}}</td>
					<td>{{$person->employee_id}}</td>
					<td><input type="text" name="branch[{{$person->person_id}}]" value="{{$person->branches}}" >
					</td>
					<td class="text text-danger">
						@foreach ($importerrors[$person->person_id]['branches'] as $invalid)
							{{$invalid}}
							{{! $loop->last ? ',' : ''}}
							<i class="fas fa-exclamation-triangle class="text text-danger"></i>
						@endforeach
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		<input class="btn btn-success" name="submit" type="submit" value="update import errors" >
	</form>
</div>

@endsection