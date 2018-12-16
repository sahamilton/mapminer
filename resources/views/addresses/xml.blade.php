<markers>
@foreach($result as $row)		
<marker
	locationweb="{{route('address.show', $row->id)}}" 
	name="{{trim($row->businessname)}}"
	account="{{trim($row->companyname)}}"
	type="{{$row->addressable_type}}"
	address="{{ trim($row->street)}} {{trim($row->city)}} {{ trim($row->state)}}"
	lat="{{ $row->lat}}"
	lng="{{ $row->lng}}"
	id="{{ $row->id}}"
/>
@endforeach
</markers>