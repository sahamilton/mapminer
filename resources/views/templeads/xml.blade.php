<markers>
@foreach($leads as $row)
	
<marker
	locationweb="{{route('salesrep.newleads.show',$row->id)}}" 
	name="{{trim($row->Company_Name)}}"
	address="{{ trim($row->Primary_Address)}} {{trim($row->Primary_City)}} {{ trim($row->Primary_State)}}"
	lat="{{ $row->lat}}"
	lng="{{ $row->lng}}"
	id="{{ $row->id}}"
	type='lead'

/>
@endforeach
</markers>