
<h2>Missing Companies</h2>
<p>Either create these companies</p>
<form name="addCompanies" action="{{route('orderimport.store')}}" method="post" >
	@csrf
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
	<thead>
		<th>Company</th>
		<th>Customer Id</th>
		<th>Create / Ignore</th>
	</thead>
	<tbody>
@if($data['missing'])
		@foreach ($data['missing'] as $company)
		<tr>
			<td>{{$company->businessname}}</td>
			<td>{{$company->customer_id}}</td>
			<td><input type="checkbox" checked name="create[]" value="{{$company->customer_id}}" /></td>
		</tr>
		@endforeach
		@endif
	</tbody>
</table>
<input type="hidden" name="type" value="companies" />
<input type="submit" name="submit" class="btn btn-success" value="Create Companies" >

</form>
