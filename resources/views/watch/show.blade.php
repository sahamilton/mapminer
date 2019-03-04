@extends('site/layouts/default')
@section('content')

<h1>{{$user->person->fullName()}}'s Watch List</h1>
<p><a href="{{route('watch.mywatchexport',$user->id)}}" 
title="Download {{$user->person->postName()}}'s Watch List as a CSV / Excel file">

<i class="fas fa-cloud-download-alt" aria-hidden="true"></i></i> Download {{$user->person->postName()}}'s Watch List</a> </p>

<table id='sorttable' class ='table table-bordered table-striped table-hover dataTable'><thead>

			
			<th>Company Name</th>
			<th>National Acct</th>
			<th>Address</th>
			<th>City</th>
			<th>State</th>
			<th>ZIP</th>
			

        
		</thead>
<tbody>
 @foreach($watch as $row)
@if(! $row->address_id)
{{dd($row)}}
@endif
			<tr>
			<td><a href="{{route('address.show',$row->address_id)}}">
			{{$row->watching->businessname}}</a></td>
			<td>{{$row->watching->company->companyname}}</td>
			<td>{{$row->watching->street}}</td>
			<td>{{$row->watching->city}}</td>
			<td>{{$row->watching->state}}</td>
			<td>{{$row->watching->zip}}</td>
				
			</tr>
@endforeach
</tbody>
</table>
@include('partials/_scripts')

@endsection
