<table>
	<thead>
		<tr></tr>
		<tr><th colspan="7">Daily Branch Statistics</th></tr>
		<tr><th colspan="7">for {{$person->fullName()}}</th></tr>
		<tr><th colspan="7">For {{$period['from']->format('M jS,Y')}}</th></tr>
		<tr></tr>
		<tr>
			<th><b>Branch Name</b></th>
			<th><b>Branch ID</b></th>
			<th><b>Branch Manager</b></th>
			<th><b># New Leads Created</b></th>
			<th><b># Log A Call Activities</b></th>
			<th><b># Sales Appointments Scheduled</b></th>
			<th><b># Sales Appointments Completed</b></th>
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
				<td>{{$branch->newleads}}</td>
				<td>{{$branch->logacall}}</td>
				<td>{{$branch->salesapptsscheduled}}</td>
				<td>{{$branch->salesappts}}</td>

			</tr>
		@endforeach
	</tbody>
</table>