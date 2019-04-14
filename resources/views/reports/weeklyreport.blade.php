<table>
	<thead>
		<tr>
			<th colspan="3">
				<h2>Top50 Open Opportunities by Branch</h2>
			</th>
		</tr>
		<tr>
			<th>
				<h4>
					For the week ending {{$period->format('M jS,Y')}}
				</h4>
			</th>
		</tr>
		<tr></tr>
		<tr>
			<th><b>Branch</b></th>
			<th><b>Count</b></th>
			<th><b>Sum of Value</b></th> 
		</tr>

	</thead>
	<tbody>
		@foreach ($opportunities as $item)
			<tr>
				<td>{{$item->branch_id}}</td>
				<td>{{$item->total}}</td>
				<td>{{$item->sumvalue}}</td>
			</tr>
		@endforeach
	</tbody>
</table>
