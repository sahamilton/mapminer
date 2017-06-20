@extends('site/layouts/default')
@section('content')

<h1>{{$user->person->firstname}} {{$user->person->lastname}}'s Watch List</h1>
<p><a href="/admin/watchlist/{{$user->id}}" 
title="Download {{$user->person->firstname}} {{$user->person->lastname}}'s Watch List as a CSV / Excel file">
<i class="glyphicon glyphicon-cloud-download"></i> Download {{$user->person->firstname}} {{$user->person->lastname}}'s Watch List</a> </p>

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
			<td>{{$row['watching'][0]->businessname}}</td>
			<td>{{$row['watching'][0]->companyname}}</td>
			<td>{{$row['watching'][0]->street}}</td>
			<td>{{$row['watching'][0]->city}}</td>
			<td>{{$row['watching'][0]->state}}</td>
			<td>{{$row['watching'][0]->zip']}}</td>
				
			</tr>
@endforeach
</tbody>
       </table>
@include('partials/_scripts')

@stop