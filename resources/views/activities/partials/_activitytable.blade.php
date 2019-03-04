<table id='sorttable5' class ='table table-bordered table-striped table-hover'>
	<thead>
		<th>Branch</th>
		@foreach($data['activitychart']['keys'] as $key)
			<th>{{$key}}</th>
		@endforeach
	</thead>
	<tbody>
		@foreach ($data['activitychart']['branches'] as $branch=>$activities)
		<tr>
			<td>{{$branch}}</td>
			@foreach($data['activitychart']['keys'] as $key)
				<td>{{$activities[$key]}}</td>

			@endforeach
		</tr>
		@endforeach
	</tbody>
</table>