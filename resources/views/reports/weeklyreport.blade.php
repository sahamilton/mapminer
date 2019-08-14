<table>
	<thead>
		<tr></tr>
		<tr><th>Top50 Open Opportunities by Branch</th></tr>
		<tr><th>For the week ending {{$period['to']->format('M jS,Y')}}</th></tr>
		<tr></tr>
		<tr>
			<th><b>Branch Id</b></th>
			
			<th><b>Count</b></th>
			<th><b>Top50</b></th>
			<th><b>Sum of Value</b></th> 
		</tr>

	</thead>
	<tbody>
		@foreach ($opportunities as $item)
			<tr>
				<td>{{$item->branch_id}}</td>
				<td>{{$item->total}}</td>
				<td>{{$item->top50}}</td>
				<td>{{$item->sumvalue}}</td>
			</tr>
		@endforeach
	</tbody>
</table>
