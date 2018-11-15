<markers>
	@foreach($persons as $row)
		@if($row->lat)
	  
			<marker

			person="{{route('person.show' , $row->id) }}"
			name="{{trim($row->firstname)}} {{trim($row->lastname)}}"
			address="{{$row->address}}"
			color="blue"
			
			lat="{{ $row->lat}}"
			lng="{{ $row->lng}}"
			id="{{ $row->id}}"
			email="{{ $row->userdetails->email}}"
			phone="{{ $row->phone}}"
			
			/>
		@endif
	@endforeach
</markers>