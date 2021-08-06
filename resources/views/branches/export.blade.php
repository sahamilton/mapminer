<table>
	<tbody>
		<tr>
			<td>branchnumber</td>

			<td>branchname</td>
			<td>street</td>
			<td>address2</td>
			<td>city</td>
			<td>state</td>
			<td>zip</td>
			<td>Country</td>
			<td>phone</td>
			<td>lat</td>
			<td>lng</td>
			<td>Service Lines</td>
			<td>Manager</td>
			<td>Reports To</td>
			
			
		</tr>
		@foreach($result as $branch)
			<tr>  

				<td>{{$branch->id}}</td>
				<td>{{$branch->branchname}}</td>
				<td>{{$branch->street}}</td>
				<td>{{$branch->address2}}</td>
				<td>{{$branch->city}}</td>
				<td>{{$branch->state}}</td>
				<td>{{$branch->zip}}</td>
				<td>{{$branch->country}}
				<td>{{$branch->phone}}</td>
				<td>{{$branch->lat}}</td>
				<td>{{$branch->lng}}</td>
				<td>
					{{implode(",",$branch->servicelines->pluck('ServiceLine')->toArray())}}
				</td>
				<td>
					@foreach($branch->manager as $manager)
						{{$manager->fullName()}} ( pid= {{$manager->id}},uid = {{$manager->user_id}}, login in past 3 months = {{$manager->userdetails->lastlogin &&  $manager->userdetails->lastlogin > now()->subMonth(3) ? 'Yes' : 'No'}} )
						@if( ! $loop->last) | @endif
					@endforeach
				</td>
				
				<td>
					
					@foreach($branch->manager as $manager)
						{{$manager->reportsTo ? $manager->reportsTo->fullName() : 'No Reporting manager'}}
						@if( ! $loop->last) | @endif
						
					@endforeach
	
				</td>
				
				
				

			</tr>
		@endforeach
	</tbody>
</table>
