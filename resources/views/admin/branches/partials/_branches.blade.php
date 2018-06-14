<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
<thead>
	<th>Branch Id</th>
	<th>Branch Name</th>
	<th>Serviceline</th>
	<th>Address</th>
	<th>City</th>
	<th>State</th>
	<th>Branch Manager</th>
	<th>Business Manager</th>
	<th>Market Manager</th>

</thead>
<tbody>
	@foreach($branches as $branch)

		<tr>
			<td><a href="{{route('branches.edit',$branch->id)}}" title="Edit {{$branch->branchname}} branch details"><i class="fa fa-pencil" aria-hidden="true"> </i>{{$branch->id}}</a>
				<a href="{{route('branches.show',$branch->id)}}" target="_blank" title="Review {{$branch->branchname}} branch details"><i class="fa fa-eye" aria-hidden="true"></i></a>
			</td>
			<td>{{$branch->branchname}}</td>
			<td>
				<ul style=" list-style-type: none;">
				@foreach ($branch->servicelines as $serviceline)
				<li>{{$serviceline->ServiceLine}}</li>
				@endforeach
			</ul>
			<td>{{$branch->street}} {{$branch->address2}}</td>
			<td>{{$branch->city}}</td>
			<td>{{$branch->state}}</td>
			<td>@if(count($branch->manager)>0)
				{{$branch->manager->first()->postName()}}
				@endif
			</td>
			<td>@if(count($branch->businessmanager)>0) 
				{{$branch->businessmanager->first()->postName()}}
				@endif
			</td>
			<td>@if(count($branch->marketmanager)>0)
				{{$branch->marketmanager->first()->postName()}}
				@endif
			</td>

		</tr>
	@endforeach
</tbody>


</table>