<?php $limit = 5;?>
	<table>
        <tbody>
<tr>
		<td>Business Name</td>
		<td>Street</td>
		<td>City</td>
		<td>State</td>
		<td>ZIP</td>
		@for($i=1;$i<$limit+1;$i++)
		<td>Branch {{$i}}</td>
		<td>Branch {{$i}} Address</td>
		<td>Branch {{$i}} Phone</td>
		<td>Branch {{$i}} Proximity (miles)</td>

		@endfor
		
   		
    
</tr>

   @foreach($locations as $location)
    <tr> 
	<td>{{$location->businessname}}</td>
	<td>{{$location->street}}</td>
	<td>{{$location->city}}</td>
	<td>{{$location->state}}</td>
	<td>{{$location->zip}}</td>
	
	<?php $branchcount =null;?>
		@if(isset($data['branches'][$location->id]))
			@foreach($data['branches'][$location->id] as $branch)
			<?php $branchcount++;?>
				<td>Branch {{$branch->id}} </td>
				<td>{{$branch->street}} {{$branch->address2}} {{$branch->city}} {{$branch->state}} {{$branch->zip}}</td>
				<td>{{$branch->phone}}</td>
				<td> {{number_format($branch->distance,0)}} </td>
			
			@endforeach
		@endif
		@for($i=0;$i<$limit-$branchcount;$i++)
			<td></td><td></td><td></td>
			<td></td>
		@endfor
		

    </tr>
   @endforeach
    
    </tbody>
</table>