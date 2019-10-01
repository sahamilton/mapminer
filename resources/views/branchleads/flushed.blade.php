<table>
	<tbody>
		<tr>
			<th>The following stale leads that were assigned to {{$manager->fullName()}}'s branches were deleted'</th>
		<tr>
			<th>id</th>
			<th>Company</th>
			<th>Street</th>
			<th>Address</th>
			<th>City</th>
			<th>State</th>
			<th>ZIP</th>
			<th>Phone</th>
			<th>Lat</th>
			<th>Lng</th>
			<th>Assigned To</th>
			
		</tr>
		@foreach($addresses as $address)
		
			<tr>  

				<td>{{$address->address_id}}</td>
				<td>{{$address->address->companyname}}</td>
				<td>{{$address->address->street}}</td>
				<td>{{$address->address->address2}}</td>
				<td>{{$address->address->city}}</td>
				<td>{{$address->address->state}}</td>
				<td>{{$address->address->zip}}</td>
				<td>{{$address->address->phone}}</td>
				<td>{{$address->address->lat}}</td>
				<td>{{$address->address->lng}}</td>
				<td>{{$address->branch->branchname}}</td>
			</tr>
		@endforeach
	</tbody>
</table>