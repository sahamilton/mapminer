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

				<td>{{$address->id}}</td>
				<td>{{$address->companyname}}</td>
				<td>{{$address->street}}</td>
				<td>{{$address->address2}}</td>
				<td>{{$address->city}}</td>
				<td>{{$address->state}}</td>
				<td>{{$address->zip}}</td>
				<td>{{$address->phone}}</td>
				<td>{{$address->lat}}</td>
				<td>{{$address->lng}}</td>
				<td>
					@foreach($address->assignedToBranch as $branch)
						{{$branch->branchname}}
					@endforeach
				</td>
			</tr>
		@endforeach
	</tbody>
</table>