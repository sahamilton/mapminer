<table>
	<thead>
		<tr></tr>
		<tr><th colspan="10">Branch Statistics</th></tr>
		<tr><th colspan="10"><h4>For the period from {{$period['from']->format('M jS,Y')}} to {{$period['to']->format('M jS,Y')}}</th></tr>
		<tr></tr>
		<tr>
			<th><b>Branch Name</b></th>
			<th><b>Branch ID</b></th>
			<th><b>Branch Manager</b></th>
			<th><b># Opportunities Opened in Period</b></th>
			<th><b># Open Top 25 Opportunities</b></th>
			<th><b># All Open Opportunities Count</b></th>
			<th><b>All Open Opportunities Value</b></th>
			<th><b># Opportunities Lost</b></th>
			<th><b># Opportunities Won</b></th>
			<th><b>Sum of Won Value</b></th>
			<th><b># Open Leads</b></th>
			<th><b># Completed Activities</b></th>
			<th><b># Completed Sales Appts</b></th>
			<th><b># Completed Site Visits</b></th>

			
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
				<td>{{$branch->Top25}}</td>
				<td>{{$branch->open}}</td>
				<td>{{$branch->openvalue}}</td>
				<td>{{$branch->lost}}</td>
				<td>{{$branch->won}}</td>
				<td>{{$branch->wonvalue}}</td>
				<td>{{$branch->leads_count}}</td>
				<td>{{$branch->activities_count}}</td>
				<td>{{$branch->salesappts}}</td>
				<td>{{$branch->sitevisits}}</td>
				
			</tr>
		@endforeach
	</tbody>
</table>