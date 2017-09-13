
<table>
	<tbody>
		<tr>
			<td>id</td>
			<td>id</td>
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
			<td>Person ID</td>
			<td>User Id</td>
		</tr>
		@foreach($result as $branch)
			<tr>  
				<td>{{$branch->id}}</td>
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
				@if(isset($branch->manager))
				<td>{{$branch->manager->postName()}}</td>
				<td>{{$branch->manager->id}}</td>
				<td>{{$branch->manager->user_id}}</td>
				@endif
				
				

			</tr>
		@endforeach
	</tbody>
</table>