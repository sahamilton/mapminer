<markers>
@foreach($markers['data'] as $marker)

<marker
	locationweb="{{route('address.show', $marker['id'])}}" 
	name="{{trim($marker['name'])}}"
	account="{{trim($marker['account'])}}"
  	type = "{{$marker['type']}}"
    distance = "{{$marker['distance']}}"
	address="{{ $marker['address']}}"
	lat="{{$marker['lat']}}"
	lng="{{$marker['lng']}}"
	id="{{$marker['id']}}"
/>
@endforeach
</markers>
