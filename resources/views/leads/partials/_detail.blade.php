<div class="list-group-item">
			<h4 class="list-group-item-text">Lead Details:</h4>
			<ul style="list-style-type: none;">
				
				<li><strong>Lead Source:</strong> {{$lead->leadsource()->first()->source}}</li>
				<li><strong>Address:</strong>{{$lead->address->address}}<br /> {{$lead->address->city}}, {{$lead->address->state}} {{$lead->address->zip}}</li>
				<li><strong>Contact:</strong> {{$lead->contacts->contact}}</li>
				<li><strong>Phone:</strong> {{$lead->contacts->contactphone}}</li>
				<li><strong>Email:</strong> {{$lead->contacts->contactemail}}</li>
				<li><strong>Date Received:</strong> {{$lead->created_at->format('M j')}}</li>
				
			
			</ul>
		</div>

		<div class="list-group">
			<div class="list-group-item">
				<h4 class="list-group-item-text">Additional Information</h4>
				<ul style="list-style-type: none;">
					@foreach ($extrafields as $field)
						<li><strong>{{$field}}</strong> {{$lead->$field}}</li>
						@endforeach
				</ul>
			</div>
		</div>