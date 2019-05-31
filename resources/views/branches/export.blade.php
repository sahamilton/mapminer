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
			<td>phone</td>
			<td>lat</td>
			<td>lng</td>
			<td>Manager</td>
			
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
				<td>{{$branch->phone}}</td>
				<td>{{$branch->lat}}</td>
				<td>{{$branch->lng}}</td>
				<td>@foreach($branch->manager as $manager)
				{{$manager->fullName()}} ( pid= {{$manager->id}},uid = {{$manager->user_id}} )
				@if( ! $loop->last) | @endif
				@endforeach
			</td>
				
				

			</tr>
		@endforeach
	</tbody>
</table>