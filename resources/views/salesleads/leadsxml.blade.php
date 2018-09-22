<markers>
@foreach($mapleads as $lead)		
<marker
	name="{{trim($lead->businessname)}}"
	address="{{ trim($lead->fullAddress())}}"
	lat="{{ $lead->lat}}"
	lng="{{ $row->lng}}"
	id="{{ $readow->id}}"
/>
@endforeach
</markers>