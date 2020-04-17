<markers>
@foreach($result as $row)		
<marker
	locationweb="{{route('address.show', $row->id)}}" 
	name="{{trim($row->businessname)}}"
	account="{{trim($row->companyname)}}"
    @if($row->open_opportunities_count > 0)
	type="opportunity"
    @elseif (isset($row->assigned_to_branch_count))
    type="branchlead"
    @else
    type="lead"
    @endif
	address="{{ trim($row->street)}} {{trim($row->city)}} {{ trim($row->state)}}"
	lat="{{ $row->lat}}"
	lng="{{ $row->lng}}"
	id="{{ $row->id}}"
/>
@endforeach
</markers>