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
		@for($i=1;$i<$limit+1;$i++)
		<td>Reps {{$i}}</td>
		<td>Reps {{$i}} distance</td>
		<td>Rep Role {{$i}}</td>
		<td>Reps {{$i}} Phone</td>
		<td>Reps {{$i}} Email</td>
		@endfor
		<td>Manager</td>
   		
    
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
		<?php $teamcount =null;?>
		@if(isset($data['salesteam'][$location->id]))
				@foreach($data['salesteam'][$location->id] as $team)
		
				<?php $teamcount++;?>
					<td>{{$team->fullName()}}</td>
					<td>  {{number_format($team->distance,1)}} miles</td>
					<td> @foreach ($team->userdetails->roles as $role)
							{{$role->name}}
						@endforeach
					</td>
					<td>{{$team->phone}}</td>
					<td>{{$team->userdetails->email}}</td>
				@endforeach
		@endif
		@for($i=0;$i<$limit-$teamcount;$i++)
			<td></td><td></td><td></td><td></td><td></td>
		@endfor
	</td>
	
		@if(count($data['salesteam'][$location->id])>0)
		
			@foreach($data['salesteam'][$location->id][0]->getAncestors()->reverse() as $managers)
			@if ($loop->first)
				@if($managers->depth != 3)
					<td></td>
				@endif
			@endif
			@if($managers->reports_to)
				<td>{{$managers->fullName()}}  {{$managers->depth}}</td>
				@endif
			@endforeach
		@endif

	
    </tr>
   @endforeach
    
    </tbody>
</table>
