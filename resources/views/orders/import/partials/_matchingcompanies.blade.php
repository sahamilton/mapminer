
<h2>Matching Companies</h2>
<p>Either link to these companies</p>
<form name="matchAddresses" action="{{route('orderimport.store')}}" method="post" >
	@csrf
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
	<thead>
		<th>Existing Company</th>
		<th>Import Company</th>
		<th>Import Street</th>
		<th>Import City</th>
		<th>Customer Id</th>
		<th>Link / Ignore</th>
	</thead>
	<tbody>

		@foreach ($data['companymatch'] as $company)
		<tr>
			
			<td>{{$company->companyname}}</td>
			
			<td>{{$company->businessname}}</td>
			<td>{{$company->street}}</td>
			<td>{{$company->city}}</td>
			<td>{{$company->customer_id}}</td>
			<td>
				<input type="checkbox" 
				checked
				name="match[{{$company->importid}}]" value="{{$company->existingid}}" />
			</td>
		@endforeach
	</tr>
	</tbody>
</table>
<input type="submit" name="submit" class="btn btn-success" value="Match Companies" />
<input type="hidden" name="type" value="companymatch" />
</form>