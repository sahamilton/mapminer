<table>
	<tbody>
		<tr>
			<td>Date</td>
			<td>Type</td>
			<td>Company</td>
			<td>address</td>
			<td>city</td>
			<td>state</td>
			<td>zip</td>
			<td>Status</td>
			<td>Note</td>
			
		</tr>
		@foreach($results as $activity)
			<tr>  
				<td>{{$activity->activity_date->format('Y-m-d')}}</td>
				<td>{{$activity->type->activity}}</td>
				<td>{{$activity->relatesToAddress->businessname}}</td>
				<td>{{$activity->relatesToAddress->street}}</td>
				<td>{{$activity->relatesToAddress->city}}</td>
				<td>{{$activity->relatesToAddress->state}}</td>
				<td>{{$activity->relatesToAddress->zip}}</td>
				<td>{{$activity->completed ? 'Complete' : 'To Do'}}
				<td>{{$activity->note}}</td>
			</tr>
		@endforeach
	</tbody>
</table>