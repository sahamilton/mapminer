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
			<td><a href="{{route('activity.branch',$branch)}}">{{$branch}}</a></td>
			@foreach($data['activitychart']['keys'] as $key)
			<td>
				@if(isset($activities[$key]))
					{{$activities[$key]}}
				@else
					0
				@endif
			</td>
			@endforeach
		</tr>
		@endforeach
	</tbody>
</table>
