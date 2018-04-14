@extends('site/layouts/default')
@section('content')

<h1>{{$user->person->postName()}}'s Watch List</h1>
<p><a href="{{route('watch.mywatchexport',$user->id)}}" 
title="Download {{$user->person->postName()}}'s Watch List as a CSV / Excel file">
<i class="fa fa-cloud-download" aria-hidden="true"></i></i> Download {{$user->person->postName()}}'s Watch List</a> </p>

<table id='sorttable' class ='table table-bordered table-striped table-hover dataTable'><thead>

			
			<th>Business Name</th>
			<th>National Acct</th>
			<th>Address</th>
			<th>City</th>
			<th>State</th>
			<th>ZIP</th>
			

        
		</thead>
<tbody>
 @foreach($watch as $row)

			<tr>
			<td><a href="{{route(
'locations.show'
,$row['watching'][0]->id)}}">
			{{$row['watching'][0]->businessname}}</a></td>
			<td>{{$row['watching'][0]->company->companyname}}</td>
			<td>{{$row['watching'][0]->street}}</td>
			<td>{{$row['watching'][0]->city}}</td>
			<td>{{$row['watching'][0]->state}}</td>
			<td>{{$row['watching'][0]->zip}}</td>
				
			</tr>
@endforeach
</tbody>
</table>
@include('partials/_scripts')

@stop