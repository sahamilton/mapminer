<table id='sorttable3' class ='table table-bordered table-striped table-hover'>
	<thead>
		
		
		<th>Companies</th>
		<th>Address</th>
		<th>Date</th>
		<th>By</th>
		<th>Comment</th>
		<th>Follow up date</th>
		<th>Contact</th>
		<th>Activity</th>
		<th>Status</th>
	</thead>
	<tbody>
		
		@foreach ($data['activities'] as $activity)
			
			<tr>
				<td>
					@if($activity->relatesToAddress)
					<a href="{{route('address.show',$activity->relatesToAddress->id)}}">

						{{$activity->relatesToAddress->businessname}}
					</a>
					@else
						{{$activity->id}}
					@endif
				</td>
				<td>
					@if($activity->relatesToAddress)
						{{$activity->relatesToAddress->fulladdress()}}
					@endif
				</td>
				<td>{{$activity->activity_date->format('Y-m-d')}}</td>
				<td>
					@if($activity->user)
					{{$activity->user->person->fullName()}}
					@else
					No longer with the company
					@endif
				</td>
				<td>{{$activity->note}}</td>
				
				<td>
					@if($activity->followup_date)
						{{$activity->followup_date->format('Y-m-d')}}
					@endif
				</td>
				<td>
					@if($activity->relatedContact)
						@foreach ($activity->relatedContact as $contact)
							{{$contact->fullname}}
						@endforeach
					@endif
				</td>
					
				<td>
					@if($activity->type)
						{{$activity->type->activity}}
					@endif
			</td>
			<td>
			
			
			@if(! $activity->completed)
                        <a title="Complete Activity"
                          href="{{route('activity.complete',$activity->id)}}" 
                          >
                          <i class="fas fa-clipboard-check"></i>
                           Mark As Complete
                        </a>	
			@else
			Completed
			@endif
			</td>
		</tr>
		@endforeach
	</tbody>

</table>