<h2>Branches to be Deleted</h2>
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
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
   @foreach($data['deletes'] as $branch)
    <tr>  
 	<td><input type="checkbox" checked name="delete[]"	value="{{$branch->id}}" />
	<td>{{$branch->branchname}}</td>

	<td>{{$branch->id}}	</td>

	<td>
	
	</td>
	<td>{{$branch->street}} {{$branch->address2}}</td>
	<td>{{$branch->city}}</td>
	<td>{{$branch->state}}</td>
    </tr>
   @endforeach
    </tbody>
    </table>


