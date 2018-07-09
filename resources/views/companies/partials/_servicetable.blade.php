<table id ='sorttable'  class='table table-striped table-bordered table-condensed table-hover'>
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

   @foreach($locations as $location)


    <tr> 
    

	<td>
		<a title= "See details of {{$location->businessname}} location."
		href={{route('locations.show',$location->id)}}>
		{{$location->businessname}}</a>
	</td>
	<td>{{$location->address->street}}</td>
	<td>{{$location->address->city}}</td>
	<td>

		<a href= "{{route('company.state', ['companyId'=>$company->id,'state'=>$location->address->state])}}"
		title="See all {{$location->address->state}} locations for $company->companyname">
		{{$location->address->state}}</a>
	</td>
	<td>{{$location->address->zip}}</td>
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