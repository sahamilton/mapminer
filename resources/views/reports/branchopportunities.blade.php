<table>
	<thead>
		<tr></tr>
		<tr><th>Branch Open Opportunities</th></tr>
		<tr><th>As of {{$period['to']->format('M jS,Y')}}</th></tr>
		<tr></tr>
		<tr>
			<th><b>Branch ID</b></th>
			<th><b>Branch Name</b></th>
			<th><b>City</b></th>
			<th><b>State</b></th>
			
			<th><b># Open Opportunities</b></th>
			<th><b>$ Value</b></th>
			<th><b>Branch Manager / Market Manager / RVP</b></th>
		</tr>
	</thead>
	<tbody>
		@foreach ($branches as $branch)

			<tr>
				<td>{{$branch->id}}</td>
				<td>{{$branch->branchname}}</td>
				<td>{{$branch->city}}</td>
				<td>{{$branch->state}}</td>
				
				<td>{{$branch->open}}</td>
				<td>${{number_format($branch->openvalue,0)}}</td>
				<td>
					@foreach($branch->manager as $manager)
						@if(! $loop->first)
							/ 
						@endif
						{{$manager->fullName()}} / 
						@foreach($manager->reportChain()->reverse() as $reportmgr)
							{{$reportmgr->fullName()}} /
						@endforeach
						<br />
					@endforeach
				</td>
			</tr>
		@endforeach
	</tbody>
</table>
