<table>

<tr><td><strong>Owned Leads - {{$leadsource->source}}</strong></td></tr>
<tr><td>Lead</td>
	<td>Address</td>
	<td>Assigned To</td>
	<td>Status</td>
	<td>Rating</td>
	<td>Notes</td>
</tr>
@foreach ($leadsource->leads as $lead)

<tr>
<td>{{ $lead->businessname }}</td>
<td>{{ $lead->fullAddress() }}</td>
@if($lead->assignedToBranch->count()>0)
	<td>{{$lead->assignedToBranch->first()->branchname}}</td>
	<td>@if($lead->assignedToBranch->first()->pivot->status_id)
			{{$statuses[$lead->assignedToBranch->first()->pivot->status_id]}}
		@endif
	</td>
	<td>{{$lead->assignedToBranch->first()->pivot->rating}}</td>
@else
	<td>
		@foreach ($lead->assignedToPerson as $person)
			{{$person->fullName()}}
			@if(! $loop->last) / @endif
		@endforeach
	</td>
	<td>{{$statuses[$lead->assignedToPerson->first()->pivot->status_id]}}</td>
	<td>{{$lead->assignedToPerson->first()->pivot->rating}}</td>	
	
@endif
@foreach ($lead->relatedNotes as $note)
@if($loop->first)
<td>
	@else
<tr>
	<td></td><td></td><td></td><td></td>
	<td></td>
	<td>
@endif
{{ $note->note }} - {{$note->created_at->format('M d, Y')}}</td>
</tr>
@endforeach

</tr>
@endforeach

</table>

