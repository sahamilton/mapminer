<table>
	<thead>
		<tr></tr>
		<tr><th>{{$company->companyname}} activities</th></tr>
		<tr><th>For the period from  {{$period['from']->format('M jS,Y')}} to {{$period['to']->format('M jS,Y')}}			</th></tr>
		
		<tr></tr>
		<tr>
			<th><b>Address</b></th>
			<th><b>Store</b></th>
			<th><b>Branch</b></th>
			<th><b>Activity Date</b></th>
			<th><b>Type</b></th>
			<th><b>Activity</b></th>
			
		</tr>

	</thead>
	<tbody>
		@foreach ($results as $address)
			@foreach ($address->activities as $activity)
				<tr>
					<td>{{$address->fullAddress()}}</td>
					<td>{{$address->address2}}</td>
					<td>{{$activity->branch_id}}</td>
					<td>{{$activity->activity_date->format('Y-m-d')}}</td>
					<td>{{$activity->type->activity}}</td>
					<td>{{$activity->note}}</td>
				
			</tr>
			@endforeach
		@endforeach
	</tbody>
</table>
