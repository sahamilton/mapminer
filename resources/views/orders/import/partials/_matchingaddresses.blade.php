
<h2>Missing Companies</h2>
<p>Either create these companies</p>
<form name="matchAddresses" action="{{route('orderimport.store')}}" method="post" >
	@csrf
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
	<thead>
		<th>Existing Business</th>
		<th>Parent Company</th>
		<th>Street</th>
		<th>City</th>
		<th>Import Company</th>
		<th>Import Street</th>
		<th>Import City</th>
		<th>Create / Ignore</th>
	</thead>
	<tbody>

		@foreach ($data['matching'] as $company)
		<tr>
			
			<td>{{$company->existingbusiness}}</td>
			<td>{{$company->parent}}</td>
			<td>{{$company->existingstreet}}</td>
			<td>{{$company->existingcity}}</td>
			<td>{{$company->importbusiness}}</td>
			<td>{{$company->importstreet}}</td>
			<td>{{$company->importcity}}</td>
			<td>
				@php $match = stripos($company->importbusiness,$company->existingbusiness) @endphp
				@if($match === false )
				
				<input type="checkbox" 
				
				name="match[{{$company->existingid}}]" value="{{$company->importid}}" />
				
				@else
				<input type="checkbox" 
				checked
				name="match[{{$company->existingid}}]" value="{{$company->importid}}" />
				<p class="invisible">1</p>

				@endif
				{{stripos($company->existingbusiness,$company->importbusiness)}}
			</td>
		@endforeach
	</tr>
	</tbody>
</table>
<input type="submit" name="submit" class="btn btn-success" value="Match Companies" />
<input type="hidden" name="type" value="addresses" />
</form>