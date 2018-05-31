<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
<thead>
	<th>Manager</th>
	<th>Employee Id</th>
	<th>Email</th>
	<th>Reports To</th>
</thead>
	<tbody>
		@foreach ($people as $manager)
		<tr>
			<td><a href="{{route('person.details',$manager->id)}}">{{$manager->fullName()}}</a></td>
			<td>{{$manager->userdetails->employee_id}}</td>
			<td>{{$manager->userdetails->email}}</td>
			<td>@if(count($manager->reportsTo)>0){{$manager->reportsTo->fullName()}}@endif</td>
		</tr>
		@endforeach
	</tbody>

</table>