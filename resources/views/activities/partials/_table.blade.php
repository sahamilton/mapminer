<table id='sorttable3' class ='table table-bordered table-striped table-hover'>
	<thead>
		
		
		<th>Company</th>
		<th>Address</th>
		<th>Date</th>
		<th>Follow up date</th>
		<th>Contact</th>
		<th>Activity</th>
	</thead>
	<tbody>
		@foreach ($activities as $activity)
			
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
				<td>
					{{$activity->activity_date->format('Y-m-d')}}</td>
				
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
				

			</tr>
		@endforeach
	</tbody>

</table>