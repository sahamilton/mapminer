@extends('site.layouts.default')
@section('content')
<h2>Missing Companies</h2>
<p>Either create these companies</p>
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
	<thead>
		<th>Company</th>
		<th>Customer Id</th>
		<th>Create / Ignore</th>
	</thead>
	<tbody>

		@foreach ($missing as $company)
			<td>{{$company->companyname}}</td>
			<td>{{$company->customer_id}}</td>
			<td><input type="checkbox" name="create[]" value="{{$company->customer_id}}" /></td>
		@endforeach
	</tbody>


</table>

@include('partials._scripts')
@endsection