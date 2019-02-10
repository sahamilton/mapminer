<table>
	<tbody>
		<tr>
			<td>Businessname</td>
			<td>Address</td>
			<td>City</td>
			<td>State</td>
			<td>Zip</td>
			<td>Contact</td>
			<td>Contact Title</td>
			<td>Phone</td>
			<td>Status</td>
			<td>Rating</td>
			<td>Notes</td>
		</tr>
		@foreach($leads as $lead)
			<tr>  
				<td>{{$lead->companyname}}</td>
				<td>{{$lead->address}}</td>
				<td>{{$lead->city}}</td>
				<td>{{$lead->state}}</td>
				<td>{{$lead->zip}}</td>
				<td>{{$lead->contacts->fullName()}}</td>
				<td>{{$lead->contacts->title}}</td>
				<td>{{$lead->contacts->contactphone}}</td>
				<td>{{$statuses[$lead->salesrep->first()->pivot->status_id]}}</td>
				<td>{{$lead->salesrep->first()->pivot->rating}}</td>
				<td>
					@foreach ($lead->relatedNotes as $notes)
						{{$notes->note}}
					@endforeach
				</td>
			</tr>
		@endforeach
	</tbody>
</table>