<markers>
@foreach($webleads as $row)
	
<marker
	locationweb="{{route('webleads.salesshow',$row->id)}}" 
	name="{{trim($row->company_name)}}"
	address="{{ trim($row->address)}} {{trim($row->city)}} {{ trim($row->state)}}"
	lat="{{ $row->lat}}"
	lng="{{ $row->lng}}"
	id="{{ $row->id}}"
	type='weblead'

/>
@endforeach
</markers>