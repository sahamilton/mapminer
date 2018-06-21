<div class="list-group-item">
			<p class="list-group-item-text">Lead Details</p>
			<ul style="list-style-type: none;">
				<li><strong>Address:</strong>{{$lead->address}}<br /> {{$lead->city}}, {{$lead->state}} {{$lead->zip}}</li>
				<li><strong>Contact:</strong>{{$lead->first_name}} {{$lead->last_name}}</li>
				<li><strong>Phone:</strong>{{$lead->phone_number}}</li>
				<li><strong>Email:</strong>{{$lead->email_address}}</li>
				<li><strong>Date Received:</strong>{{$lead->created_at->format('M j')}}</li>
				
			
			</ul>
		</div>

		<div class="list-group">
			<div class="list-group-item">
				<p class="list-group-item-text">Job Requirements</p>
				<ul style="list-style-type: none;">
						<li><strong>Time Frame:</strong>{{$lead->time_frame}}</li>
						<li><strong>Jobs:</strong>{{$lead->jobs}}</li>
						<li><strong>Industry:</strong>{{$lead->industry}}</li>
				</ul>
			</div>
		</div>