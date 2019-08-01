<table>
	<thead>
		<tr><th colspan="3">Sales Meetings and Won Opportunities by Branch</th></tr>
		<tr><th>For the period  {{$period['from']->format('M jS,Y')}} to {{$period['to']->format('M jS,Y')}}</th></tr>
		<tr></tr>
		<tr>
			<th><b>Branch</b></th>
			<th><b>Branch Name</b></th>
			<th><b>Sales Meetings</b></th>
			<th><b>Opportunities Won</b></th>
			<th><b>Sum of Value</b></th> 
		</tr>

	</thead>
	<tbody>
		@foreach ($results as $item)

			<tr>
				<td>{{$item->branch_id}}</td>
				<td>{{$item->branchname}}</td>
				<td>
					@if($item->salesmeetings)
						{{$item->salesmeetings}}
					@endif
				</td>
				<td>
					@if($item->opportunitieswon) 
						{{$item->opportunitieswon}}
					@endif
				</td>
				<td>
					@if($item->value)
						{{$item->value}}
					@endif
				</td>
			</tr>
		@endforeach
	</tbody>
</table>
