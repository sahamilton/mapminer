<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
    	<th>National Account</th> 
		<th>Company Name</th> 
		<th>Industry Vertical</th>
		<th>Street </th> 
		<th>City </th> 
		<th>State </th> 
		<th>ZIP </th> 


    </thead>
    <tbody>
   @foreach($locations as $location)

    <tr>  
    <td>
    	@if($location->company_id)
<a href="{{route('company.show',$location->company_id)}}"
				title="See all {{$location->companyname}} locations">
				{{$location->companyname}}
		</a>
		@endif
    </td>
	<td>
		<a href="{{route('address.show',$location->id)}}"
				title="See details of the {{$location->businessname}} location">
				{{$location->businessname}}
		</a>
	</td>
	<td>{{$location->vertical}}</td>
	<td>{{$location->street}}</td>
	<td>{{$location->city}}</td>
	<td>{{$location->state}}</td>
	<td>{{$location->zip}}</td>

    </tr>
   @endforeach
    
    </tbody>
    </table>