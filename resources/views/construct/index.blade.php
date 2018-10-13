@extends('admin.layouts.default')
@section('content')
<h2>Nearby Construction Projects</h2>
@include('construct.partials._form')
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
	<thead>
		<th>Project Name</th>
		<th>Description</th>
		<th>Project Type</th>
		<th>Project Value</th>
		<th>Address</th>
		<th>Created</th>

	</thead>
	<tbody>
		@foreach($projects as $project)

		<tr>
			
			<td><a href="{{route('construction.show',$project['_source']['id'])}}">{{$project['_source']['siteaddresspartial']}}</a></td>
			<td>{{$project['_source']['description']}}</td>
			<td>{{$project['_source']['construction']['construction_type']}}</td>
			<td>${{number_format($project['_source']['valuation'],0)}}</td>
			<td>{{$project['_source']['siteaddress']}}</td>

			<td>{{$project['_source']['createdate']->format('Y-m-d')}}</td>

		</tr>
		@endforeach
	</tbody>
</table>
@include('partials._scripts')
@endsection