<h2>Lead Additional Details</h2>
	

	@foreach($extrafields as $field )
		<p><strong>{{ucwords(str_replace("_"," ",$field))}}:</strong>{{ $lead->$field }}</p>
	@endforeach
	
