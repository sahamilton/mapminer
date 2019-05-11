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
			<th><b>Count</b></th>
			<th><b>Sum of Value</b></th> 
		</tr>

	</thead>
	<tbody>
		@foreach ($branches as $branch)
		{{dd($branch)}}
			<tr>
				<td>{{$item->branch_name}}</td>
				
				<td>
					@if($item->branch && $item->branch->manager)
						{{$item->branch->manager->fullName()}}
					@endif
				</td>
				<td>{{$item->total}}</td>
				<td>{{$item->sumvalue}}</td>
			</tr>
		@endforeach
	</tbody>
</table>
