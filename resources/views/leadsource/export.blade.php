<table>

<tr><td><strong>Owned Prospects - {{$leadsource->source}}</strong></td></tr>
<tr><td>Prospect</td>
	<td>Address</td>
	<td>Owned By</td>
	<td>Status</td>
	<td>Rating</td>
	<td>Notes</td>
</tr>
@foreach ($leadsource->leads as $lead)

<tr>
<td>{{$lead->companyname}}</td>
<td>{!! $lead->fullAddress() !!}</td>
<td>{{$lead->ownedBy[0]->postName()}}</td>
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