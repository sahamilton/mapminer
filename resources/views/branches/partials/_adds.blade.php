<h2>Branches to be Added</h2>
<table id ='sorttablenosort' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
    <th></th>
    <th>Branch</th>
	<th>Number</th>
	<th>Service Line</th>
	<th>Branch Address</th>
	<th>City</th>
	<th>State</th>
	
    </th>
    </thead>
    <tbody>
   @foreach($data['adds'] as $branch)
  
    <tr>  
 	<td><input type="checkbox" checked name="add[]"	value="{{$branch->id}}" />
	<td>{{$branch->branchname}}</td>

	<td>{{$branch->id}}	</td>

	<td>
		@foreach ($branch->servicelines as $serviceline)
			<li>{{$serviceline->ServiceLine}}
		@endforeach

	</td>
	<td>{{$branch->street}} {{$branch->address2}}</td>
	<td>{{$branch->city}}</td>
	<td>{{$branch->state}}</td>
    </tr>
   @endforeach
    </tbody>
    </table>

