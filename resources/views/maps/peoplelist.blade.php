@extends('site.layouts.default')


@section('content')

<h1>{{$data['title']}}</h1>


@include('maps.partials._form')
<table id='sorttable' class ='table table-bordered table-striped table-hover'>
	<thead>
		<th>Full Name</th>
		<th>Role</th>
		<th>Address</th>
		
		<th>Miles</th>

	</thead>
	<tbody>
	
		@foreach ($data['result'] as $person)
	
			<tr>
				<td>{{$person->fullName()}}</td>
				<td>
					@foreach ($person->userdetails->roles as $role)
					<li>{{$role->name}}</li>
					@endforeach
				<td>
					@if($person->address =='')
						{{$person->city}}, {{$person->state}}
					@else
						{{$person->address}}
					@endif
				</td>
				
				<td>{{number_format($person->distance,1)}}</td>
				

			</tr>
		@endforeach
	</tbody>

</table>

   
@include('partials/_scripts')

@endsection
