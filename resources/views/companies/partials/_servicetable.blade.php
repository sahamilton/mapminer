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

   @foreach($company->locations as $location)


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
		<ul>
		@if(isset($branches[$location->id]))
		@foreach($branches[$location->id] as $branch)
			<li>{{$branch->branchname}} {{number_format($branch->distance,0)}} miles 
			</li>
		@endforeach
		@endif
		</ul>
	</td>
	<td>
		<ul>
	
			@if(isset($salesteam[$location->id]))
			
				@foreach($salesteam[$location->id] as $team)
				<li>
					{{$team->postName()}}  {{number_format($team->distance,1)}} miles
				</li>
				@endforeach
			@endif
	</ul>
	</td>

	
    </tr>
   @endforeach
    
    </tbody>
</table>