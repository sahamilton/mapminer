<div class="list-group-item">
			<h4 class="list-group-item-text">Lead Details:</h4>
			<ul style="list-style-type: none;">
				<li><strong>Lead Source:</strong> {{$lead->leadsource()->first()->source}}</li>
				<li><strong>Address:</strong>{{$lead->address->address}}<br /> {{$lead->address->city}}, {{$lead->address->state}} {{$lead->address->zip}}</li>
				<li><strong>Contact:</strong> {{$lead->first_name}} {{$lead->last_name}}</li>
				<li><strong>Phone:</strong> {{$lead->phone}}</li>
				<li><strong>Email:</strong> {{$lead->contactemail}}</li>
				<li><strong>Date Received:</strong> {{$lead->created_at->format('M j')}}</li>
				
			
			</ul>
		</div>

		<div class="list-group">
			<div class="list-group-item">
				<h4 class="list-group-item-text">Job Requirements</h4>
				<ul style="list-style-type: none;">
						<li><strong>Time Frame:</strong> {{$lead->time_frame}}</li>
						<li><strong>Jobs:</strong> {{$lead->jobs}}</li>
						<li><strong>Industry:</strong> {{$lead->industry}}</li>
				</ul>
			</div>
		</div>