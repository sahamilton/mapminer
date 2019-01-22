@extends('site.layouts.default')
@section('content')
<h1>Service Team for {{$company->companyname}}</h1>
<p><i class="fab fa-pagelines"></i> <a href="{{route('company.service',$company->id)}}">Show Branches Servicing {{$company->companyname}}</a></p>

<p><a href="{{route('company.show',$company->id)}}">
	Return to all locations of {{$company->companyname}}</a></p>
<p><i class="fas fa-cloud-download-alt"></i> <a href="{{route('company.teamservice.export',$company->id)}}">Export to Excel</a></p>
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

				<li>{{$role->displayName}}</li>
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