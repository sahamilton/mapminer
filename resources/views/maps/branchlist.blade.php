<table id='sorttable' class ='table table-bordered table-striped table-hover dataTable'><thead>
	<thead>
		<th>Branch Name</th>
		<th>Service Line</th>
		<th>Address</th>
		<th>City</th>
		<th>State</th>
		<th>ZIP</th>
		<th>Miles</th>
	</thead>
	<tbody>
		@foreach($data['result'] as $row)
			<tr>  

				<td><a href="{{route('branches.show',$row->branchid)}}">{{$row->branchname}}</a></td>
				<td>{{$row->servicelines}}</td>
				<td>{{$row->street}}</td>
				<td>{{$row->city}}</td>
				<td>{{$row->state}}</td>
				<td>{{$row->zip}}</td>
				<td>{{$row->distance_in_mi}}</td>
			</tr>
		@endforeach
	</tbody>
</table>