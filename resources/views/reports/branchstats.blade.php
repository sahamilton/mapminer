<table>
	<thead>
		<tr><th colspan="10"><h2>Branch Statistics</h2></th></tr>
		<tr><th colspan="10"><h4>For the period from {{$period['from']->format('M jS,Y')}} to {{$period['to']->format('M jS,Y')}}</h4></th></tr>
		<tr></tr>
		<tr>
			<th><b>Branch Name</b></th>
			<th><b>Branch ID</b></th>
			<th><b>Branch Manager</b></th>
			<th><b>Opportunities Opened</b></th>
			<th><b>Open Opportunities Count</b></th>
			<th><b>Open Opportunities Value</b></th>
			<th><b>Open Top 50 Opportunities</b></th>
			<th><b>Opportunities Won</b></th>
			<th><b>Opportunities Lost</b></th>
			<th><b>Sum of Won Value</b></th>
			<th><b>Open Leads</b></th>
			<th><b>Completed Activities</b></th>
			
		</tr>

	</thead>
	<tbody>
		@foreach ($branches as $branch)
		
			<tr>
				<td>{{$branch->branchname}}</td>
				<td>{{$branch->id}}</td>
				<td>
					@foreach ($branch->manager as $manager)
					{{$manager->fullName()}}
					@if(! $loop->last)/@endif
					@endforeach
				</td>
				<td>{{$branch->opened}}</td>
				<td>{{$branch->open}}</td>
				<td>{{$branch->openvalue}}</td>
				<td>{{$branch->top50}}</td>
				<td>{{$branch->lost}}</td>
				<td>{{$branch->won}}</td>
				<td>{{$branch->wonvalue}}</td>
				<td>{{$branch->leads_count}}</td>
				<td>{{$branch->activities_count}}</td>
				
			</tr>
		@endforeach
	</tbody>
</table>