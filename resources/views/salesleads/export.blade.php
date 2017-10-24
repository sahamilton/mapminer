<table>
<tr>
<td>Owned and Closed Prospects of {{auth()->user()->person->fullName()}}</td>

</tr>
<tr><td><strong>Owned Prospects</strong></td></tr>
@foreach ($leads->owned as $lead)
<tr>


</tr>

@endforeach
<tr><td><strong>Closed Prospects</strong></td></tr>
@foreach ($leads->closed as $lead)
<tr>


</tr>

@endforeach
</table>