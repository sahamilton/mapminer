<markers>
	@foreach($persons as $row)
		@if($row->lat)
	  
			<marker

			person="{{route('person.show' , $row->id) }}"
			name="{{trim($row->firstname)}} {{trim($row->lastname)}}"
			address="{{$row->address}}"
			@if(isset($row->industryfocus[0]))
			
				@if($row->industryfocus[0]->id ==14)
				
					industry="{{"General"}}"
				@else
					industry="{{$row->industryfocus[0]->filter}}"
				@endif
				brand="{{$row->industryfocus[0]->id}}"
				color="{{$row->industryfocus[0]->color}}"
			@endif
			@if(isset($row->reportsTo))
				salesorg="{{route('salesorg',$row->reportsTo->id)}}"
				reportsto="{{trim($row->reportsTo->firstname)}} {{trim($row->reportsTo->lastname)}}"

			@endif

			lat="{{ $row->lat}}"
			lng="{{ $row->lng}}"
			id="{{ $row->id}}"
			email="{{ $row->userdetails->email}}"
			phone="{{ $row->phone}}"
			type="{{"industry"}}"
			/>
		@endif
	@endforeach
</markers>