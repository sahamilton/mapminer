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
		@endfor
		@for($i=1;$i<$limit+1;$i++)
		<td>Reps {{$i}}</td>
		@endfor
		<td>Manager</td>
   		
    
</tr>

   @foreach($company->locations as $location)


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
				<td>Branch {{$branch->id}} : {{number_format($branch->distance,0)}} miles </td>
			
			@endforeach
		@endif
		@for($i=0;$i<$limit-$branchcount;$i++)
			<td></td>
		@endfor
		<?php $teamcount =null;?>
		@if(isset($data['salesteam'][$location->id]))
				@foreach($data['salesteam'][$location->id] as $team)
				<?php $teamcount++;?>
					<td>{{$team->postName()}}  {{number_format($team->distance,1)}} miles</td>
				@endforeach
		@endif
		@for($i=0;$i<$limit-$teamcount;$i++)
			<td></td>
		@endfor
	</td>
	
		@if(count($data['salesteam'][$location->id])>0)
		
			@foreach($data['salesteam'][$location->id][0]->getAncestors()->reverse() as $managers)
			@if($managers->reports_to)
				<td>{{$managers->postName()}}</td>
				@endif
			@endforeach
		@endif

	
    </tr>
   @endforeach
    
    </tbody>
</table>