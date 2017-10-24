<table>
<tr>
<td>Owned and Closed Prospects</td>

</tr>
<tr><td><strong>Owned Prospects</strong></td></tr>
@foreach ($leadssource->leads as $lead)
<tr>
<td>{{$lead->companyname}}</td>
<td>{{$lead->fullAddress}}</td>
<td>{{$lead->pivot->status_id}}</td>
<td>{{$lead->ownedBy->fullName()}}</td>
@foreach ($lead->relatedNotes as $note)
<tr>
	<td></td><td></td><td></td><td></td>
	<td>{{$note->note}}</td>
</tr>
@endforeach
</tr>
@endforeach

</table>