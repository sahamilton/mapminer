<markers>
@foreach($result as $row)		
<marker
	locationweb="{{route(
'locations.show'
 , $row->id)}}" 
	name="{{trim($row->businessname)}}"
	account="{{trim($row->companyname)}}"
	accountweb="{{route('company.show' , $row->company_id,array('title'=>'see all locations') )}}"
	address="{{ trim($row->street)}} {{trim($row->city)}} {{ trim($row->state)}}"
	lat="{{ $row->lat}}"
	lng="{{ $row->lng}}"
	id="{{ $row->id}}"
	vertical="{{ $row->vertical}}"
/>
@endforeach
</markers>