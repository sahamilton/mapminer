<markers>
@foreach($markers['data'] as $row)

<marker
	locationweb ="{{route('address.show', $row['id'])}}" 
	name ="{{$row['name']}}"
	account ="{{$row['account']}}"
    type="{{$row['type']}}"
	
	address="{{ $row['address']}}"
	lat="{{ $row['lat']}}"
	lng="{{ $row['lng']}}"
	id="{{ $row['id']}}"
/>
@endforeach
</markers>