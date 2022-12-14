<table>
	<tbody>
		<tr>
			<td>id</td>
			<td>businessname</td>
			<td>street</td>
			<td>suite</td>
			<td>city</td>
			<td>state</td>
			<td>zip</td>
			<td>Lat</td>
			<td>Lng</td>
			<td>company_id</td>
			<td>phone</td>
			<td>contact</td>
			<td>segment</td>
			<td>lat</td>
			<td>lng</td>
			<td>Assigned to Branch(es)</td>

		</tr>
		@foreach($company->locations as $location)
			<tr>  
			<td>{{$location->id}}</td>
			<td>{{$location->businessname}}</td>
			<td>{{$location->street}}</td>
			<td>{{$location->address2}}</td>
			<td>{{$location->city}}</td>
			<td>{{$location->state}}</td>
			<td>{{$location->zip}}</td>
			<td>{{$location->lat}}</td>
			<td>{{$location->lng}}</td>
			<td>{{$location->company_id}}</td>
			<td>{{$location->phone}}</td>
			<td>{{$location->contact}}</td>
			<td>{{$location->segment}}</td>
			<td>{{$location->lat}}</td>
			<td>{{$location->lng}}</td>
			<td>
				{{implode(" | ", $location->assignedtoBranch->pluck('branchname')->toArray())}}

			</td>
			</tr>
		@endforeach
	</tbody>
</table>
