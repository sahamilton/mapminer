<table id='sorttable3' class ='table table-bordered table-striped table-hover'>
	<thead>
		
		
		<th>Companies</th>
		<th>Address</th>
		<th>Date</th>
		<th>Created By</th>
		<th>Notes</th>
		<th>Contact</th>
		<th>Activity</th>
		<th>Status</th>
	</thead>
	<tbody>
		
		@foreach ($data['activities'] as $activity)
			@if(! $activity->completed)
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
				<td>
					<a href="#" 
					class="editable" 
					data-type="textarea" 
					data-pk="{{$activity->id}}" 
					data-url="{{route('api.note.edit',$activity->id)}}" 
					data-name="note"
					data-title="Edit note">{{$activity->note}}</a></td>
				
				
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
		@endif
		@endforeach
	</tbody>

</table>