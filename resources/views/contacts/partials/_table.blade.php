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
					@if($contact->location)
					<a href="{{route('address.show',$contact->location->id)}}">
						{{$contact->location->businessname}}
					</a>
					@endif
				</td>
				<td>
					{{$contact->fullname}}
				</td>

				<td>
					@if($contact->location)
						{{$contact->location->fullAddress()}}
					@endif
				</td>
				<td>{{$contact->contactphone}}</td>
				<td><a href="mailto:{{$contact->email}}">{{$contact->email}}</td>
				

			</tr>
		@endforeach
	</tbody>

</table>