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
				<td>{{$lead->address->address}}</td>
				<td>{{$lead->address->city}}</td>
				<td>{{$lead->address->state}}</td>
				<td>{{$lead->address->zip}}</td>
				<td>{{$lead->contacts->contact}}</td>
				<td>{{$lead->contacts->contacttitle}}</td>
				<td>{{$lead->contacts->phone}}</td>
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