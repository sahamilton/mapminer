@extends('site.layouts.default')
@section('content')
<h1>Branch Contacts</h1>  
<table id='sorttable' class ='table table-bordered table-striped table-hover'>
	<thead>
		<th>Contact</th>
		<th>Company</th>

	</thead>
	<tbody>
		@foreach ($contacts as $contact)

			<tr>
				<td>{{$contact->fullname}}</td>
				<td>
					<a href="{{route('address.show',$contact->location->id)}}">
						{{$contact->location->businessname}}
					</a>
				</td>
				
				
				

			</tr>
		@endforeach
	</tbody>

</table>
   
@include('partials/_scripts')

@endsection