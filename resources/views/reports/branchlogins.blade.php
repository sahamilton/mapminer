<table>
	<thead>
		<tr><th colspan="4"><h2>Branch Logins</h2></th></tr>
		<tr><th colspan="4"><h4>For the period from {{$period['from']->format('M jS,Y')}} to {{$period['to']->format('M jS,Y')}}</h4></th></tr>
		<tr></tr>
		<tr>
			<th><b>Branch ID</b></th>
			<th><b>Branch Name</b></th>
			<th><b>Logins</b></th>
			<th><b>Avg Daily</b></th>
		</tr>
	</thead>
	<tbody>
		
		@foreach ($results as $result)
			<tr>
				<td>{{$result->branchid}}</td>
				<td>{{$result->branchname}}</td>
				<td>{{$result->logins}}</td>
				<td>{{$result->avgdaily}}</td>
				
			</tr>
		@endforeach
	</tbody>
</table>
