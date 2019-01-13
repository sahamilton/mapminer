@extends('site.layouts.default')
@section('content')
@include('companies.partials._searchbar')
@include('maps.partials._form')
<h1>Branch Contacts</h1>  

<table id='sorttable' class ='table table-bordered table-striped table-hover'>
	<thead>
		
		<th>Company</th>
		<th>Contact</th>
		<th>Addess</th>
		<th>Phone</th>
		<th>Email</th>

	</thead>
	<tbody>
		@foreach ($contacts as $contact)

			<tr>
				
				<td>
					<a href="{{route('address.show',$contact->location->id)}}">
						{{$contact->location->businessname}}
					</a>
				</td>
				<td>{{$contact->fullname}}</td>
				<td>{{$contact->location->fullAddress()}}</td>
				<td>{{$contact->phone}}</td>
				<td><a href="mailto:{{$contact->email}}">{{$contact->email}}</td>
				

			</tr>
		@endforeach
	</tbody>

</table>
   
@include('partials/_scripts')

@endsection