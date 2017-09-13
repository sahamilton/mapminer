<h2>Nearby Branches</h2>
<p>The closest branches that can serve the {{$project->project_title}} project:</p>

<table class="table table-striped table-bordered table-condensed">
	<thead>
		<th>Branch</th>
		<th>Branch #</th>
		<th>Address</th>
		<th>City</th>
		<th>State</th>
		<th>Distance</th>
	</thead>
	<tbody>

		@foreach($branches as $branch)
		<tr>

			<td>
			<a href="{{route('branches.show',$branch->branchid)}}" 
			title="Review {{trim($branch->branchname)}} branch">
			{{$branch->branchname}}</a>
			</td>
			<td>{{$branch->branchid}}</td>
			<td>{{$branch->street}}</td>
			<td>{{$branch->city}}</td>
			<td>{{$branch->state}}</td>
			<td>{{number_format($branch->distance_in_mi,2)}} miles away.</td>

		</tr>
	 	@endforeach
	</tbody>
 </table>