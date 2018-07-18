<h2>Branches to be Changed</h2>
<table id ='nosorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
    <th></th>
    <th>Branch</th>
	<th>Number</th>
	<th>Address Changes</th>
	<th>City Changes</th>
	<th>State Changes</th>
	<th>ZIP Changes</th>

    </thead>
    <tbody>
   @foreach($data['changes'] as $branch)

    <tr>  
 	<td><input type="checkbox" checked name="change[]"	value="{{$branch->branchid}}" />
	<td>{{$branch->branchname}}</td>

	<td>{{$branch->branchid}}	</td>

	<td>
		@if($branch->orgstreet !== $branch->newstreet or $branch->orgaddress2 !== $branch->newaddress2)
		{{$branch->orgstreet . ' ' . $branch->orgaddress2 }} => {{$branch->newstreet . ' ' .$branch->newaddress2}}
		@endif
	</td>
	
	<td>
		@if($branch->orgcity !== $branch->newcity)
		{{$branch->orgcity}} => {{$branch->newcity}}
		@endif
	</td>
	<td>
		@if($branch->orgcity !== $branch->newcity)
		{{$branch->orgcity}} => {{$branch->newcity}}
		@endif
	</td>
	<td>
		@if($branch->orgzip !== $branch->newzip)
		{{$branch->orgzip}} => {{$branch->newzip}}
		@endif
	</td>
	
    </tr>
   @endforeach
    </tbody>
    </table>


