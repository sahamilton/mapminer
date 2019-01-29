<markers>
@foreach($result->locations as $row)

<marker
	locationweb="{{route('address.show', $row->id)}}" 
	name="{{trim($row->businessname)}}"
	account="{{trim($row->companyname)}}"
	@if($row->company_id)
	accountweb="{{route('company.show' , $row->company_id,array('title'=>'see all locations') )}}"
	@endif
	address="{{ trim($row->street)}} {{trim($row->city)}} {{ trim($row->state)}}"
	lat="{{ $row->lat}}"
	lng="{{ $row->lng}}"
	id="{{ $row->id}}"
	vertical="{{ $row->vertical}}"
	locationid="{{$row->locationid}}"
/>
@endforeach
</markers>