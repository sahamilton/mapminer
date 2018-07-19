<h2>Branches to be Changed</h2>
<table id ="nosorttable" class="table table-striped table-bordered table-condensed table-hover">
    <thead>
    <th></th>
    <th>Branch</th>
	<th>Number</th>
	<th>Address Changes</th>
	<th>Phone Changes</th>
	

    </thead>
    <tbody>
   @foreach($data['changes'] as $branch)

   		@php
   		$newaddress = $branch->newstreet . " ". $branch->newaddress2 . " " . $branch->newcity . " " . $branch->newstate . " " . $branch->newzip;
   		$oldaddress = $branch->orgstreet . " ". $branch->orgaddress2 . " ". $branch->orgcity . " " . $branch->orgstate . " " . $branch->orgzip;
   		@endphp
    <tr>  
 	<td><input type="checkbox" checked name="change[]"	value="{{$branch->branchid}}" />
	<td>{{$branch->branchname}}</td>

	<td>{{$branch->branchid}}	</td>

	<td>
		@if($newaddress !== $oldaddress)
		Old:{{$oldaddress }}  <br />New:{{$newaddress}}
		
		@endif
	</td>
	
	<td>
		
		@if($branch->newphone !== $branch->orgphone)
		{{$branch->orgphone}} => {{$branch->newphone}}
		@endif
	</td>
	
    </tr>
   @endforeach
    </tbody>
    </table>


