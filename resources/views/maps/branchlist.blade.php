<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>

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

				<td><a href="{{route('branches.show',$row->id)}}">{{$row->branchname}}</a></td>
				<td>
				@foreach ($row->servicelines as $serviceline)
					{{$serviceline->ServiceLine}}
				@endforeach
				</td>
				<td>{{$row->street}}</td>
				<td>{{$row->city}}</td>
				<td>{{$row->state}}</td>
				<td>{{$row->zip}}</td>
				<td>{{number_format($row->distance,1)}}</td>
			</tr>
		@endforeach
	</tbody>
</table>