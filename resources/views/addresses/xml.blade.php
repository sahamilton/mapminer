<markers>
@foreach($markers['data'] as $row)
<marker
	locationweb ="{{$row['locationsweb']}}" 
	name ="{{$row['name']}}"
	account ="{{$row['account']}}"
    type="{{$row['type']}}"
	distance="{{$row['distance']}}"
	address="{{ $row['address']}}"
	lat="{{ $row['lat']}}"
	lng="{{ $row['lng']}}"
	id="{{ $row['id']}}"
/>
@endforeach
</markers>