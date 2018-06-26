<markers>
@foreach($leads as $row)
	
<marker
	locationweb="{{route('salesrep.newleads.show',$row->id)}}" 
	name="{{trim($row->companyname)}}"
	address="{{ trim($row->address)}} {{trim($row->city)}} {{ trim($row->state)}}"
	lat="{{ $row->lat}}"
	lng="{{ $row->lng}}"
	id="{{ $row->id}}"
	type='lead'

/>
@endforeach
</markers>