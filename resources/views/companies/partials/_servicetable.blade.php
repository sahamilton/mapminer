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
	<td>{{$location->street}}</td>
	<td>{{$location->city}}</td>
	<td>

		<a href= "{{route('company.state', ['companyId'=>$company->id,'state'=>$location->state])}}"
		title="See all {{$location->state}} locations for $company->companyname">
		{{$location->state}}</a>
	</td>
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
				
					{{$team->fullName()}}  {{number_format($team->distance,1)}} miles
				<br/>
				@endforeach
			@endif
	
	</td>

	
    </tr>
   @endforeach
    
    </tbody>
</table>