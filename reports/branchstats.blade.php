<table>
	<thead>
		<tr>
			<th colspan="3">
				<h2>Branch Statistics</h2>
			</th>
		</tr>
		<tr>
			<th>
				<h4>
					For the period from {{$period['from']->format('M jS,Y')}} to {{$period['to']->format('M jS,Y')}}
				</h4>
			</th>
		</tr>
		<tr></tr>
		<tr>
			<th><b>Branch Name</b></th>
			<th><b>Branch ID</b></th>
			<th><b>Branch Manager</b></th>
			<th><b>Open Leads</b></th>
			<th><b>Avg Velocity</b></th>
			<th><p>Sales Appts</p></th>
			<th><p>Opportunities Open</p></th>
			<th><p>Opportunities Won</p></th>
			<th><p>Opportunities Lost</p></th>
			<th><p>Open Top 50 Opportunities</p></th>
			<th><b>Sum of Won Value</b></th> 
		</tr>

	</thead>
	<tbody>
		@foreach ($branches as $branch)

			<tr>
				<td>{{$branch->branch_name}}</td>
				<td>{{$branch->id}}</td>
				<td>
					@foreach ($branch->manager as $manager)
					<li>{{$manager->fullName()}}</li>
					@endforeach
				</td>
				<td>{{$branch->leads_count}}</td>
				<td>{{$branch->activities_count}}</td>
				<td></td>
				<td>{{$branch->salesappts}}</td>
				<td>{{$branch->won}}</td>

				<td>{{$branch->open}}</td>
				<td>{{$branch->lost}}</td>
				<td>{{$branch->top50}}</td>
				<td>{{$branch->open}}</td>
				<td>{{$branch->wonvalue}}</td>
			</tr>
		@endforeach
	</tbody>
</table>