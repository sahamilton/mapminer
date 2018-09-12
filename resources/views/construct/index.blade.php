@extends('admin.layouts.default')
@section('content')
<h2>Publications</h2>
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
	<thead>
		<th>Id</th>
		<th>Year</th>
		<th>Week</th>
		<th>Area</th>
		<th>Created</th>

	</thead>
	<tbody>
		@foreach($collection['Publications'] as $item)
		<tr>
			
			<td>{{$item['publicationId']}}</td>
			<td>{{$item['year']}}</td>
			<td>{{$item['week']}}</td>
			<td>{{$item['areaid']}}</td>
			<td>{{$item['createdate']}}</td>
		</tr>
		@endforeach
	</tbody>
</table>
@include('partials._scripts')
@endsection