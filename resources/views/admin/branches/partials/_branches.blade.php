<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
<thead>
	<th>Branch Id</th>
	<th>Branch Name</th>
	<th>Address</th>
	<th>City</th>
	<th>State</th>
</thead>
<tbody>
	@foreach($branches as $branch)

		<tr>
			<td><a href="{{route('branches.edit',$branch->id)}}">{{$branch->id}}</a></td>
			<td>{{$branch->branchname}}</td>
			<td>{{$branch->street}} {{$branch->address2}}</td>
			<td>{{$branch->city}}</td>
			<td>{{$branch->state}}</td>

		</tr>
	@endforeach
</tbody>


</table>