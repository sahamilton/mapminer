@extends('admin.layouts.default')
@section('content')
<h2>Construction Projects of {{$company[0]['_source']['companylinks'][0]['company']['name']}}</h2>
<p><a href="{{route('construction.index')}}">Return to all projects</a></p>
<p>{{$company[0]['_source']['companylinks'][0]['company']['address']}}<br/>
{{$company[0]['_source']['companylinks'][0]['company']['city']}}
{{$company[0]['_source']['companylinks'][0]['company']['state']}}
{{$company[0]['_source']['companylinks'][0]['company']['zip']}}</p>

<p><i class="fas fa-phone" aria-hidden="true"></i>
t
{{$company[0]['_source']['companylinks'][0]['company']['phone']}}</p>


<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
	<thead>
		<th>Project Name</th>
		<th>Role</th>
		<th>Description</th>
		<th>Project Type</th>
		<th>Project Value</th>
		<th>Address</th>
		<th>Created</th>

	</thead>
	<tbody>
		@foreach($company as $project)
		<tr>
			<td><a href="{{route('construction.show',$project['_source']['id'])}}">{{$project['_source']['siteaddresspartial']}}</a></td>
			<td>{{$project['_source']['companylinks'][0]['companylinktype']}}</td>
			<td>{{$project['_source']['description']}}</td>
			<td>{{$project['_source']['construction']['construction_type']}}</td>
			<td>${{number_format($project['_source']['valuation'],0)}}</td>
			<td>{{$project['_source']['siteaddress']}}</td>
			<td>{{Carbon\Carbon::parse($project['_source']['createdate'])->format('Y-m-d')}}</td>
		</tr>
		@endforeach
	</tbody>
</table>
@include('partials._scripts')
@endsection