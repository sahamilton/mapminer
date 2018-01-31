<table>
    <thead>

		<th>Business Name</th>
		<th>Street</th>
		<th>City</th>
		<th>State</th>
		<th>ZIP</th>
		<th>Branches</th>
		<th>Reps</th>
   		
    </thead>
    <tbody>

   @foreach($company->locations as $location)


    <tr> 
    

	<td>{{$location->businessname}}</td>
	<td>{{$location->street}}</td>
	<td>{{$location->city}}</td>
	<td>{{$location->state}}</td>
	<td>{{$location->zip}}</td>
	<td>
		
		@if(isset($data['branches'][$location->id]))
		@foreach($data['branches'][$location->id] as $branch)
			Branch {{$branch->id}}  {{number_format($branch->distance,0)}} miles 
			<br/>
		@endforeach
		@endif
		
	</td>
	<td>
			@if(isset($data['salesteam'][$location->id]))
				@foreach($data['salesteam'][$location->id] as $team)
					{{$team->postName()}}  {{number_format($team->distance,1)}} miles
				<br/>
				@endforeach
			@endif
	</td>
    </tr>
   @endforeach
    
    </tbody>
</table>