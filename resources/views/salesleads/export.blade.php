<table>
<tr>
<td>Owned and Closed Leads of {{auth()->user()->person->fullName()}}</td>
</tr>
<tr><td>Lead</td>
	<td>Address</td>
	<td>Contact</td>
	<td>Contact Title</td>
	<td>Contact Phone</td>
	<td>Owned By</td>
	<td>Status</td>
	<td>Rating</td>
	<td>Notes</td>
</tr>
@foreach ($leads->ownedLeads as $lead)
<tr>
<td>{{$lead->companyname}}</td>
<td>{!! $lead->fullAddress() !!}</td>
<td>{{$lead->contacts->contact}}</td>
<td> {{$lead->contacts->contacttitle}}</td>
<td>{{$lead->contacts->contactphone}}</td>
<td>{{$lead->ownedBy[0]->fullName()}}</td>
<td>{{$statuses[$lead->ownedBy[0]->pivot->status_id]}}</td>
<td>{{$lead->ownedBy[0]->pivot->rating}}</td>

@foreach ($lead->relatedNotes as $note)
@if($loop->first)
<td>
	@else
<tr>
	<td></td><td></td><td></td><td></td>
	<td></td>
	<td>
@endif
{{$note->note}} - {{$note->created_at->format('M d, Y')}}</td>
</tr>
@endforeach
</tr>
@endforeach
</table>