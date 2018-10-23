@extends('site.layouts.default')
@section('content')
<h1>Service Team for {{$company->companyname}}</h1>

<div class="container" >
	<table class="table" id="sorttable">
	<thead>
		<th>Name</th>
		<th>Role</th>
		<th>Email</th>
		
	</thead>
	<tbody>
		@foreach($team as $person)
		<tr>
			<td>{{$person->fullName()}}</td>
			<td>
				@foreach($person->userdetails->roles as $role)

				<li>{{$role->name}}</li>
				@endforeach
			</td>
			<td>{{$person->userdetails->email}}</td>
		</tr>
		@endforeach

	</tbody>

	</table>
	</div>
</div>


@include('partials._scripts')
@endsection