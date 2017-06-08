<h2>Closest Branches</h2>
<table id ='sorttable1' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>

    <th>Branch</th>
	<th>Number</th>
	<th>Service Line</th>
	<th>Branch Address</th>
	<th>City</th>
	<th>Distance</th>

       
    </thead>
    <tbody>
   @foreach($branches as $branch)
    <tr>  

	
	<td>
		<a href="{{route('branches.show',$branch->branchid)}}" 
		 title="See details of branch {{$branch->branchname}}">
		{{$branch->branchname}}
		</a>
	</td>
	
	<td>
	{{$branch->branchnumber}}
	</td>

	<td>

						{{$branch->servicelines}}
					
	</td>

	<td>
			{{$branch->street}} {{$branch->address2}}
	</td>

	<td>
			{{$branch->city}}

	</td>

	<td>
	{{number_format($branch->distance_in_mi,0)}}
	</td>
	
	</tr>
	@endforeach
	</tbody>
	</table>